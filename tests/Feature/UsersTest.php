<?php

namespace Tests\Feature;

use App\Models\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    /**
     * @test
     */
    public function whenSendUserWithCorrectValuesInPostReturnUserCratedWithToken() //Register User
    {
        $this->seed();
        // cuando envio el usuario con sus datos correctos
        $response = $this->post('/users', [
            "user" => [
                "name" => "Cristian Gonzalez",
                "email" => "cristiangt9@gmail.com",
                "street" => "calle 17 conjunto Chibará",
                "postal" => "17005",
                "city" => "Cucuta",
                "password" => "password"
            ]
        ]);
        // recibo un usuario creado y su token para poder crear una sesion en le frontend
        $response->assertStatus(201);
        $response->assertJson(
            function (AssertableJson $json) {
                $json->has(
                    'user',
                    function ($json) {
                        $json->where('id', 1)
                            ->where('name', 'Cristian Gonzalez')
                            ->missing('password')
                            ->has('token');
                    }
                );
            }
        );
    }
    /**
     * @test
     */
    public function whenSendCorrectUserCrentialsInGetReturnUserWithToken() //Login correct
    {
        $this->seed();
        // Primero se crea el usuario de prueba
        $newUSer = new User();
        $newUSer->name = "Cristian Gonzalez";
        $newUSer->email = "cristiangt9@gmail.com";
        $newUSer->street = "calle 17 conjunto Chibará";
        $newUSer->postal = "17005";
        $newUSer->city = "Cucuta";
        $newUSer->password = "passwor";

        // cuando envio el las credenciales del usuario correctos
        $response = $this->get('/users/loginApp', [
            "email" => "cristiangt9@gmail.com",
            "password" => "password"
        ]);
        // recibo un usuario loguedo y su token para poder crear una sesion en le frontend
        $response->assertStatus(201);
        $response->assertJson(
            function (AssertableJson $json) {
                $json->has(
                    'user',
                    function ($json) {
                        $json->where('id', 1)
                            ->where('name', 'Cristian Gonzalez')
                            ->missing('password')
                            ->has('token');
                    }
                );
            }
        );
    }
    public function whenSendInCorrectUserCrentialsIGetReturnUserWithToken() //Login Incorrect
    {
        $this->seed();
        // Primero se crea el usuario de prueba
        $newUSer = new User();
        $newUSer->name = "Cristian Gonzalez";
        $newUSer->email = "cristiangt9@gmail.com";
        $newUSer->street = "calle 17 conjunto Chibará";
        $newUSer->postal = "17005";
        $newUSer->city = "Cucuta";
        $newUSer->password = "passwor";
        // cuando envio el las credenciales del usuario incorrectos
        $response = $this->get('/users/loginApp', [
            "email" => "cristiangt9@gmail.com",
            "password" => "passwordd"
        ]);
        // recibo un aviso de credenciales incorrectas y sin ningun usuario
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) {
                $json->missing('user');
            }
        );
    }
}