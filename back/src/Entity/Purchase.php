<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table]

class Purchase
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer', unique: true)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[Groups(['View', 'Create'])]
    protected ?int $id;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    #[Groups(['View', 'Create'])]
    protected Product $product;

    #[ORM\Column(type: 'string', length: 13, nullable: false)]
    #[Groups(['View', 'Create'])]
    #[Assert\Length(min: 11, max: 13)]
    #[Assert\Regex(pattern: '/^[A-Z]{2}([A-Z0-9]{2})?[0-9]{9}?$/')]
    protected string $taxNumber;

    #[ORM\Column(type: 'string', length: 10, nullable: false)]
    #[Assert\Length(max: 10)]
    #[Groups(['View', 'Create'])]
    protected string $couponCode;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Groups(['View', 'Create'])]
    protected string $paymentProcessor;

    #[ORM\Column(type: 'float')]
    #[Assert\Range(min: 0)]
    #[Groups(['View'])]
    protected float $totalPrice = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(string $taxNumber): void
    {
        $this->taxNumber = $taxNumber;
    }

    public function getCouponCode(): string
    {
        return $this->couponCode;
    }

    public function setCouponCode(string $couponCode): void
    {
        $this->couponCode = $couponCode;
    }

    public function getPaymentProcessor(): string
    {
        return $this->paymentProcessor;
    }

    public function setPaymentProcessor(string $paymentProcessor): void
    {
        $this->paymentProcessor = $paymentProcessor;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }
}
