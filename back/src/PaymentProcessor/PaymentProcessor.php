<?php

namespace App\PaymentProcessor;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class PaymentProcessor
{
    // можно было бы сделать адаптеры для процессоров и использовать стратегию
    public const PAYPAL_PROCESSOR = 'paypal';
    public const STRIPE_PROCESSOR = 'stripe';

    public const PROCESSORS = [
        self::PAYPAL_PROCESSOR,
        self::STRIPE_PROCESSOR,
    ];

    public function __construct(
        private readonly PaypalPaymentProcessor $paypalPaymentProcessor,
        private readonly StripePaymentProcessor $stripePaymentProcessor,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function processPayment(float $price, string $processor): bool
    {
        if (!in_array($processor, self::PROCESSORS)) {
            throw new BadRequestException('The processor is not supported', code: Response::HTTP_BAD_REQUEST);
        }
        $result = false;
        switch ($processor) {
            case self::PAYPAL_PROCESSOR:
                $this->paypalPaymentProcessor->pay($price * 100);
                $result = true;
                break;
            case self::STRIPE_PROCESSOR:
                $result = $this->stripePaymentProcessor->processPayment($price);
                break;
        }

        return $result;
    }
}
