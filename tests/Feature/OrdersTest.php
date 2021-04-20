<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;
    /**
     * @test
     */
    public function whenSaveOrderReturnOrderCreatedAndUserCreated()
    {
        $this->seed();
        $response = $this->post('/orders', [
            "meals" => [
                [
                    "id" => 1,
                    "name" => "Sushi",
                    "description" => "Finest fish and veggies",
                    "price" => 22.99,
                    "amount" => 4
                ],
                [
                    "id" => 2,
                    "name" => "Schnitzel",
                    "description" => "A german specialty!",
                    "price" => 16.50,
                    "amount" => 2
                ]
            ],
            "user" => [
                "name" => "Cristian Gonzalez",
                "email" => "cristiangt9@gmail.com",
                "street" => "calle 17 conjunto Chibará",
                "postal" => "17005",
                "city" => "Cucuta",
                "password" => "password"
            ]
        ]);
        $response->assertStatus(201);
        // dd($response->getContent());
        $response->assertJson(
            function (AssertableJson $json) {
                $json->has(
                    'data',
                    function ($json) {
                        $json->has(
                            'order',
                            function ($json) {
                                $json->where('id', 1)
                                    ->has('user_id')
                                    ->has('created_at')
                                    ->has('updated_at')
                                    ->has('meals.0', function ($json) {
                                        $json->where('id', 1)
                                            ->where('name', 'Sushi')
                                            ->has('description')
                                            ->has('created_at')
                                            ->has('updated_at')
                                            ->has('price')
                                            ->has('pivot', function ($json) {
                                                $json
                                                    ->where('meal_id', 1)
                                                    ->where('order_id', 1)
                                                    ->where('amount', 4)
                                                    ->has('created_at')
                                                    ->has('updated_at');
                                            });
                                    })
                                    ->has('meals.1')
                                    ->has('user');
                            }
                        );
                    }
                )
                ->has('success')
                ->has('title')
                ->has('message')
                ->has('messages')
                ->has('code');
            }
        );
        
    }
}
