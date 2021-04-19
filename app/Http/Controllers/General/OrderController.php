<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Services\OrderService;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validar lo que llego
        $rules = [
            "meals" => "required|array",
            "user" => "required"
        ];
        $validacionRequest = $this->validateRequestJson($request->all(), $rules);

        if (!$validacionRequest->validated) {
            return response()->json(["success" => false, "title" => "Datos faltantes", "message" => "Hay datos obligatorios no enviados","messages" => $validacionRequest->errors], 422);
        }
        // iniciar el modo transaccion
        try {
            // crear el usuario
            $userResponse = User::createNew($request->user);
            // crear la orden
            $orderServiceResponse = $this->orderService->create($request->meals, $userResponse["user"]->id);
            
            return response()->json(["order" => $orderServiceResponse["order"]], 201);
        } catch (\Exception $e) {
            // May day,  rollback!!! rollback!!!
            return response()->json(["success" =>false, "title" => "Lo sentimos, pero algo fallo", "message" => $e->getMessage(), "messages" => [$e]], 422);
        }
        
        
        // retornar la data creada
        // o retornar el error
        return response()->json([], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
