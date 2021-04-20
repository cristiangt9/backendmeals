<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // validar
        $rules = [
            "name" => "required",
            "email" => "required",
            "street" => "required",
            "postal" => "required",
            "city" => "required",
            "password" => "requiredd"
        ];
        $validationRequest = $this->validateRequestJson($request, $rules);
        if ($validationRequest->validated) {
            $this->defaultJsonResponseWithoutData(false, "Datos faltantes", "Hay datos que no cumplen con la validación", $validationRequest->errors, 422);
        }

        try {

        } catch (\Exception $th) {
            
        }
    }
}
