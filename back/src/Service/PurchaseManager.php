<?php

namespace App\Service;

use App\Entity\Coupon;
use App\Entity\Purchase;
use Doctrine\ORM\EntityManagerInterface;

class PurchaseManager
{
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
        }

        return $price;
    }
}
