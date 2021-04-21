<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
// use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MealsTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;
    /**
     * @test
     */
    public function whenReciveGetMealsReturnMeals()
    {
        $this->seed();
        $response = $this->get('/meals', ["Accept" => "application/json"]); // cuando recibe una solicitud a meals de tipo get
        // entonces debe responder el index con todas las meals
        // dd($response->getContent());
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('success', true)
                ->has('title')
                ->has('message')
                ->has('messages')
                ->has('code')
                ->has('data', function ($jsonData) {
                    $jsonData->has('meals', 4);
                });
        });
    }
}
