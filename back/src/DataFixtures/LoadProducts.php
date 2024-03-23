<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadProducts extends Fixture
{
    use EntityExistenceTait;
    private const LIST = [
        [
            'price' => 100,
            'name' => 'Iphone',
        ],
        [
            'price' => 20,
            'name' => 'Наушники',
        ],
        [
            'price' => 10,
            'name' => 'Чехол',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::LIST as $item) {
            var_dump($item);
            if (!$this->isEntityExist($manager, Product::class, 'name', $item)) {
                $group = $this->createProduct($item);
                $manager->persist($group);
            }
        }

        $manager->flush();
    }

    public function createProduct($fields): Product
    {
        $group = new Product();

        $group->setName($fields['name']);
        $group->setPrice($fields['price']);

        return $group;
    }
}
