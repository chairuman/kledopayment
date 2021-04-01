<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class EndPointTest extends TestCase
{
    /**
     * funtion to test endpoint GET /api/payments
     * and check return json structure
     *
     * @return void
     */
    use WithFaker;
    
    public function test_get_all_payments()
    {
        $this->json('get', '/api/payments')
             ->assertStatus(200)
             ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'payment_name',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'path',
                    'per_page',
                    'to'
                ]
            ]);
    }

    /**
     * function to test endpoint POST /api/payments
     * and also check json structure
     * @return void
     */
    public function test_add_payment()
    {
        $data = [
            'payment_name' => $this->faker->name
        ];

        $this->json('POST', '/api/payments', $data)
             ->assertStatus(200)
             ->assertJsonStructure([
                 'status',
                 'message'
             ]);
    }

    /**
     * funtion to test endpoint DELETE /api/payments
     * and check json structure
     * @return void
     */
    public function test_delete_payment()
    {
        $data = [
            'payment_id' => '45'
        ];
        $this->withoutExceptionHandling();
        $this->json('DELETE', '/api/payments', $data)
             ->assertStatus(200)
             ->assertJsonStructure([
                 'status',
                 'message'
             ]);
    }
}
