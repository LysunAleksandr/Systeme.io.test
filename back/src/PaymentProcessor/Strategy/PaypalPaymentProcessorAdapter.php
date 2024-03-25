<?php

namespace App\PaymentProcessor\Strategy;

use App\PaymentProcessor\PaypalPaymentProcessor;
use Exception;

class PaypalPaymentProcessorAdapter extends PaypalPaymentProcessor implements PaymentProcessorInterface
{
    /**
     * @throws Exception
     */
    public function processPayment(float $price): bool
    {
        $this->pay($price * 100);
        return true;
    }
}
