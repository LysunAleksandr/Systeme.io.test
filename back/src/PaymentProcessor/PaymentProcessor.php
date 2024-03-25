<?php

namespace App\PaymentProcessor;

use App\PaymentProcessor\Strategy\PaymentProcessorInterface;
use Exception;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class PaymentProcessor
{
    /**
     * @throws Exception
     */
    public function processPayment(float $price, string $processor): bool
    {
        $paymentProcessor = $this->getProcessorByName($processor);

        return $paymentProcessor->processPayment($price);
    }

    private function getProcessorByName(string $processor): PaymentProcessorInterface
    {
        $adapterClass = __NAMESPACE__ . '\\Strategy\\' . $processor . 'PaymentProcessorAdapter';

        if (!class_exists($adapterClass)) {
            throw new BadRequestException('The processor is not supported', code: Response::HTTP_BAD_REQUEST);
        }

        return new $adapterClass();
    }
}
