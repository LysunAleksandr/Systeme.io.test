<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

trait EntityExistenceTait
{
    public function isEntityExist(ObjectManager $manager, string $entityClass, string $field, $value): bool
    {
        return (bool) $manager->getRepository($entityClass)->findOneBy([$field => $value]);
    }
}
