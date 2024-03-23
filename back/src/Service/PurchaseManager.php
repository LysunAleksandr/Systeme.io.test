<?php

namespace App\Service;

use App\Entity\Coupon;
use App\Entity\Purchase;
use App\PaymentProcessor\PaymentProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PurchaseManager
{
    // TODO хранить в базе как справочник
    public const TAX = [
        'DE' => 19,
        'IT' => 22,
        'GR' => 24,
        'FR' => 20,
    ];

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected PaymentProcessor $paymentProcessor,
        protected ValidatorInterface $validator,
    ) {
    }

    public function setPurchasePrice(Purchase $purchase)
    {
        $errors = $this->validator->validate($purchase);
        if ($errors->count() > 0) {
            throw new BadRequestException($errors, code: Response::HTTP_BAD_REQUEST);
        }

        $price = $purchase->getProduct()->getPrice();
        if ($couponCode = $purchase->getCouponCode()) {
            $coupon =  $this->entityManager->getRepository(Coupon::class)->findOneBy(['couponCode' => $couponCode]);
            if ($coupon instanceof Coupon) {
                if ($coupon->isProcent()) {
                    $price = $price * (1 - $coupon->getDiscount() / 100);
                } else {
                    $price = $price - $coupon->getDiscount() > 0 ? $price - $coupon->getDiscount() : $price;
                }
            }
            $taxCode = substr($purchase->getTaxNumber(), 0, 2);
            if (array_key_exists($taxCode, self::TAX)) {
                $price = $price * (1 + self::TAX[$taxCode]/100);
            } else {
                throw new BadRequestException('The TaxNumber is not supported', code: Response::HTTP_BAD_REQUEST);
            }
        }
        $purchase->setTotalPrice(round($price, 2));
        return $purchase;
    }

    /**
     * @throws \Exception
     */
    public function buy(Purchase $purchase)
    {
        $purchase = $this->setPurchasePrice($purchase);
        if ($this->paymentProcessor->processPayment($purchase->getTotalPrice(), $purchase->getPaymentProcessor())) {
            $this->entityManager->persist($purchase);
            $this->entityManager->flush();
        }

        return $purchase;
    }
}
