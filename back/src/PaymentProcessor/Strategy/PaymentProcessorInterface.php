<?php

namespace App\PaymentProcessor\Strategy;

interface PaymentProcessorInterface
{
    public function processPayment(float $price): bool;
}