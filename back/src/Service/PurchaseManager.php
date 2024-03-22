<?php

namespace App\Service;

use App\Entity\Coupon;
use App\Entity\Purchase;
use Doctrine\ORM\EntityManagerInterface;

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
    ) {
    }

    public function getPurchasePrice(Purchase $purchase)
    {
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
            }
        }

        return round($price, 2);
    }
}
