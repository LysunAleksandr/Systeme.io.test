<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadCoupons extends Fixture
{
    use EntityExistenceTait;
    private const LIST = [
        [
            'couponCode' => 'D15',
            'procent' => true,
            'discount' => 15,
        ],
        [
            'couponCode' => 'A20',
            'procent' => false,
            'discount' => 20,
        ],
        [
            'couponCode' => 'D10',
            'procent' => true,
            'discount' => 10,
        ],
        [
            'couponCode' => 'A10',
            'procent' => false,
            'discount' => 10,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::LIST as $item) {
            var_dump($item);
            if (!$this->isEntityExist($manager, Coupon::class, 'couponCode', $item)) {
                $group = $this->createProduct($item);
                $manager->persist($group);
            }
        }

        $manager->flush();
    }

    public function createProduct($fields): Coupon
    {
        $group = new Coupon();

        $group->setCouponCode($fields['couponCode']);
        $group->setProcent($fields['procent']);
        $group->setDiscount($fields['discount']);

        return $group;
    }
}
