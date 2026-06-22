<?php

namespace Tests;

use App\Services\StripePaymentService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->mock(StripePaymentService::class, function ($mock) {
            $mock->shouldReceive('createPaymentIntent')
                ->andReturnUsing(function ($order, $email) {
                    return [
                        'payment_intent_id' => 'pi_test_123',
                        'client_secret'     => 'pi_test_123_secret',
                        'payment_url'       => 'https://ipureherbs.org/checkout/payment?order_id='
                            . $order->order_number
                            . '&payment_intent=pi_test_123',
                    ];
                });
        });
    }
}
