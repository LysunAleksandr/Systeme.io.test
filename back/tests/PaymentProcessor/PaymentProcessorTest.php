<?php

namespace App\Tests\PaymentProcessor;

use App\PaymentProcessor\PaymentProcessor;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class PaymentProcessorTest  extends TestCase
{
    public function provideDate(): iterable
    {
        yield ['price' => 10, 'processor' => 'paypal', 'result' => true];
        yield ['price' => 100, 'processor' => 'stripe', 'result' => true];
     }

    /**
     * @throws Exception
     */
    public function testProcessPayment()
    {
        $processor = new PaymentProcessor();

        foreach ($this->provideDate() as $date) {
            $this->assertSame($processor->processPayment($date['price'],$date['processor']), $date['result']);
        }

        $this->expectException(BadRequestException::class);
        $processor->processPayment(10,'paypopal');
    }


}