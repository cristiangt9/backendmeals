<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\CredentialsIncorrect;
use App\Exceptions\FailduringCreate;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['store','loginApp']);
    }
    public function store(Request $request)
    {
        // validar
        $rules = [
            "name" => "required",
            "email" => "required|unique:users,email",
            "street" => "required",
            "postal" => "required",
            "city" => "required",
            "password" => "required"
        ];
        $validationRequest = $this->validateRequestJson($request->all(), $rules);
        if (!$validationRequest->validated) {
            $this->defaultJsonResponseWithoutData(false, "Datos faltantes", "Hay datos que no cumplen con la validación", $validationRequest->errors, 422);
        }

        try {
            //creo el usuario
            $userCreateResponse = User::createNew($request->all());
            if ($userCreateResponse["success"]) {
                $user = $userCreateResponse["user"];
                $token = $user->createToken('accessToken');
                $user = $user->toArray();
                $user['token'] = $token->accessToken;
                return $this->defaultJsonResponse(true, "Usuario creado", "El usuario ha sido creado satisfactoriamente", null, ["user" => $user], 201);
            } else {
                throw new FailduringCreate($userCreateResponse["error"]->getMessage());
            }
            // respondo con el usuario creado y con token
        } catch (\Exception $e) {
            // respondo con los errores ocurridos
            return $this->defaultJsonResponseWithoutData(false, "Algo fallo", $e->getMessage(), [$e], 422);
        }
    }

    public function loginApp(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        $validationRequest = $this->validateRequestJson($request->all(), $rules);
        if (!$validationRequest->validated) {
            return $this->defaultJsonResponseWithoutData(false, "Datos faltantes", "Hay datos que no cumplen con la validación", $validationRequest->errors, 422);
        }

        try {
            $user = User::where("email", $request->email)->first();
            
            if ($user == null || !Hash::check($request->password, $user->password)) {
                throw new CredentialsIncorrect("Credenciales Incorrectas");
            }

            $token = $user->createToken('accessToken');
            $user = $user->toArray();
            $user['token'] = $token->accessToken;
            return $this->defaultJsonResponse(false, "Acceso Concedido", "Las credenciales son correctas, acceso concedido", [], ["user" => $user]);
        } catch (\Exception $e) {

            return $this->defaultJsonResponseWithoutData(false, "Algo fallo", $e->getMessage(), [$e], 422);
        }
    }
}
