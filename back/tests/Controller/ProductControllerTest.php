<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    public function provideJson(): iterable
    {
        yield ['json' => '{"product": 1,"taxNumber": "DE123456789","couponCode": "D15","paymentProcessor": "Paypal"}', 'result' => Response::HTTP_OK];
        yield ['json' => '{"product": 1,"taxNumber": "DE123456789","couponCode": "D15","paymentProcessor": "Paypopal"}', 'result' => Response::HTTP_BAD_REQUEST];
        yield ['json' => '{"product": 1,"taxNumber": "DE1234U56789","couponCode": "D15","paymentProcessor": "Paypal"}', 'result' => Response::HTTP_BAD_REQUEST];
    }

    public function testCalculatePriceAction()
    {
        $client = static::createClient();
        foreach ($this->provideJson() as $request) {
            $client->request('POST', '/api/v1/products/calculate-price',
                [],
                [],
                ['Content-Type' => 'application/json'],
                '{"product": 1,"taxNumber": "DE123456789","couponCode": "D15"}'
            );
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
        }

        foreach ($this->provideJson() as $request) {
            $client->request('POST', '/api/v1/products/purchase',
                [],
                [],
                ['Content-Type' => 'application/json'],
                $request['json']
            );
            $this->assertEquals($request['result'], $client->getResponse()->getStatusCode());
        }
    }
}