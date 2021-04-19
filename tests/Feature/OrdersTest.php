<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
                "city" => "Cucuta"
            ]
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            "orden" => [
                "id" => "1",
                "meals" =>
                [
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
                "user_id" => 1,
                "user" => [
                    "id" => 1,
                    "name" => "Cristian Gonzalez",
                    "email" => "cristiangt9@gmail.com",
                    "street" => "calle 17 conjunto Chibará",
                    "postal" => "17005",
                    "city" => "Cucuta"
                ]
            ]
        ]);
    }
}
