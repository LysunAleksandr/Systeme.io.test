<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table]

class Coupon
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer', unique: true)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['View'])]
    protected ?int $id;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Groups(['View'])]
    protected string $couponCode;

    #[ORM\Column(type: 'boolean', nullable: false)]
    #[Groups(['View'])]
    protected bool $procent = true;

    #[ORM\Column(type: 'float')]
    #[Groups(['View'])]
    protected float $discount = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCouponCode(): string
    {
        return $this->couponCode;
    }

    public function setCouponCode(string $couponCode): void
    {
        $this->couponCode = $couponCode;
    }

    public function isProcent(): bool
    {
        return $this->procent;
    }

    public function setProcent(bool $procent): void
    {
        $this->procent = $procent;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): void
    {
        $this->discount = $discount;
    }
}
