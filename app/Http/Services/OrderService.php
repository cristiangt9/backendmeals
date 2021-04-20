<?php

namespace App\Http\Services;

use App\Exceptions\FailduringCreate;
use App\Exceptions\ResourceNotFound;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function create(array $meals, int $user_id)
    {
        try {
            DB::beginTransaction();

            $order = new Order();
            $order->user_id = $user_id;
            if ($order->save()) {

                // crear relacion con las meals
                foreach ($meals as $meal) {
                    Log::info($order->id . "-" . $meal["id"] . " => " . $meal["amount"]);
                    $order->meals()->attach($meal["id"], ["amount" => $meal["amount"]]);
                }

                Log::info($order);
                $order = Order::with(['meals', 'user'])->find($order->id);
                if (!$order) {
                    throw new ResourceNotFound("orden no encontrada despues de haber sido guardada");
                }
            } else {
                throw new FailduringCreate("ocurrio un error mientras se guardaba la orden");
            }
            DB::commit();
            return ["success" => true, "order" => $order];
        } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            Log::info($e->getMessage());
        }
    }
}
