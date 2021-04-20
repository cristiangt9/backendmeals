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
        parent::__construct();
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
            "user" => "required",
            "user.name" => "required",
            "user.email" => "required|unique:users,email",
            "user.street" => "required",
            "user.postal" => "required",
            "user.city" => "required",
            "user.password" => "required"
        ];
        $validacionRequest = $this->validateRequestJson($request->all(), $rules);

        if (!$validacionRequest->validated) {
            return $this->defaultJsonResponseWithoutData(false, "Datos faltantes", "Hay datos que no cumplen con la validación", $validacionRequest->errors, 422);
        }

        // iniciar el modo transaccion
        try {

            // crear el usuario
            $userResponse = User::createNew($request->user);
            // crear la orden
            $orderServiceResponse = $this->orderService->create($request->meals, $userResponse["user"]->id);
            
            return $this->defaultJsonResponse(true, "Orden generada","La orden fue generada satisfactoriamente",null, ["order" => $orderServiceResponse["order"]], 201);
        } catch (\Exception $e) {
            // reporting errors
            return $this->defaultJsonResponseWithoutData(false, "Lo sentimos, pero algo fallo", $e->getMessage(), [$e], 422);
        }
        
        
        // retornar la data creada
        // o retornar el error
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
