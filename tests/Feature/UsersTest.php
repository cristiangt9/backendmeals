<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }
    /**
     * @test
     */
    public function whenSendUserWithCorrectValuesInPostReturnUserCreatedWithToken() //Register User
    {
        $this->seed();
        // cuando envio el usuario con sus datos correctos
        $response = $this->post('/users', [
            "name" => "Cristian Gonzalez",
            "email" => "cristiangt9@gmail.com",
            "street" => "calle 17 conjunto Chibará",
            "postal" => "17005",
            "city" => "Cucuta",
            "password" => "password"
        ],["Accept" => "application/json"]);
        // recibo un usuario creado y su token para poder crear una sesion en le frontend
        $response->assertStatus(201);
        $response->assertJson(
            function (AssertableJson $json) {
                $json->has('success')
                    ->has("title")
                    ->has("message")
                    ->has("messages")
                    ->has("code")
                    ->has("data", function ($json) {
                        $json->has(
                            'user',
                            function ($json) {
                                $json->where('id', 1)
                                    ->where('name', 'Cristian Gonzalez')
                                    ->missing('password')
                                    ->has('token')
                                    ->has('email')
                                    ->has('street')
                                    ->has('postal')
                                    ->has('city')
                                    ->has('updated_at')
                                    ->has('created_at');
                            }
                        );
                    });
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
        $newUSer = [];
        $newUSer['name'] = "Cristian Gonzalez";
        $newUSer['email'] = "cristiangt9@gmail.com";
        $newUSer['street'] = "calle 17 conjunto Chibará";
        $newUSer['postal'] = "17005";
        $newUSer['city'] = "Cucuta";
        $newUSer['password'] = "password";
        User::createNew($newUSer);

        // cuando envio el las credenciales del usuario correctos
        $response = $this->post('/users/loginApp', [
            "email" => "cristiangt9@gmail.com",
            "password" => "password"
        ],["Accept" => "application/json"]);
        // recibo un usuario loguedo y su token para poder crear una sesion en le frontend
        $response->assertStatus(200);
        $response->assertJson(
            function (AssertableJson $json) {
                $json->has('success')
                    ->has("title")
                    ->has("message")
                    ->has("messages")
                    ->has("code")
                    ->has("data", function ($json) {
                        $json->has(
                            'user',
                            function ($json) {
                                $json->where('id', 1)
                                    ->where('name', 'Cristian Gonzalez')
                                    ->missing('password')
                                    ->has('token')
                                    ->has('email')
                                    ->has('street')
                                    ->has('postal')
                                    ->has('city')
                                    ->has('updated_at')
                                    ->has('created_at');
                            }
                        );
                    });
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
