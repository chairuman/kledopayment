<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;
class RequestValidationTest extends TestCase
{
    use WithFaker;
    /**
     * function to test when POST /api/payment with no payment_name request
     * and the response will return 422 (unprocessable entity)
     * @return void
     */
    public function test_request_should_fail_when_no_payment_name()
    {
        $this->json('POST', '/api/payments',[])
             ->assertStatus(422)
             ->assertJsonValidationErrors('payment_name');
    }

    /**
     * funtion to test when POST /api/payments with payment_name request
     * and the response will return 200 (ok)
     * @return void
     */
    public function test_request_should_pass_when_has_payment_name()
    {
        $this->json('POST', '/api/payments', ['payment_name' => $this->faker->name])
             ->assertStatus(200)
             ->assertJsonMissingValidationErrors(['payment_name']);
    }

    /**
     * function to test when DELETE /api/payments with no payment_id param
     * the response will return 422 (unprocessable entity)
     * @return void
     */
    public function test_request_should_fail_when_no_payment_id()
    {
        $this->json('DELETE', '/api/payments', [])
             ->assertStatus(422)
             ->assertJsonValidationErrors('payment_id');
    }

    /**
     * funtion to test when DELETE /api/payments with payment_id param
     * the response will return 200 (ok)
     * @return void
     */
    public function test_request_shoud_pass_when_has_payment_id()
    {
        $this->json('DELETE', '/api/payments', ['payment_id' => '46'])
             ->assertStatus(200)
             ->assertJsonMissingValidationErros(['payment_id']);
    }
}
