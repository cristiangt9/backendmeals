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
        $response = $this->get('/meals'); // cuando recibe una solicitud a meals de tipo get
        // entonces debe responder el index con todas las meals
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('success', true)
                ->has('title')
                ->has('message')
                ->has('messages')
                ->has('code')
                ->has('data', function ($jsonData) {
                    $jsonData->has('meals', function ($jsonMeals)
                    {
                        $jsonMeals->has(4);
                    });
                });
        });
    }
}
