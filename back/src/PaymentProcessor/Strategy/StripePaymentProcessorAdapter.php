<?php

namespace App\PaymentProcessor\Strategy;

use App\PaymentProcessor\StripePaymentProcessor;

class StripePaymentProcessorAdapter extends StripePaymentProcessor implements PaymentProcessorInterface
{
}
