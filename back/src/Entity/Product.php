<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table]

class Product
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer', unique: true)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['View'])]
    protected int $id;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\Length(max: 255)]
    #[Groups(['View'])]
    protected string $name;

    #[ORM\Column(type: 'float')]
    #[Assert\Range(min: 0)]
    #[Groups(['View'])]
    protected float $price = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}
