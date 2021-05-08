<?php

namespace App\Http\Controllers;

use App\Models\Foods;
use App\Models\Order;
use App\Models\OrderFoods;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function show(Request $request) {
        return Order::with('foods')->where('user_id', $request->user_id)->get();
    }


    public function store(Request $request) {
        $order = new Order();
        $order->user_id = $request->user_id;
        $order->save();
        $order_food = new OrderFoods();
        $order_food->order_id = $order->id;
        $order_food->foods_id = $request->id;
        $order_food->save();
        return response([
            'order' => $order,
        ], Response::HTTP_ACCEPTED);
    }

    public function deletefoodoforder(Request $request) {
        $order = OrderFoods::where('foods_id', $request->foods_id)->where('order_id', $request->order_id)->delete();
        return response([
            'message' => 'success',
        ], Response::HTTP_ACCEPTED);
    }

    public function addfoodsorder(Request $request) {
        $arr = [];
        $foods = Foods::all();
        $newfoods = $request->foods;
        foreach ($foods as $f){
            foreach ($newfoods as $add){
                if ($add === $f->name){
                    array_push($arr, $f);
                }
            }
        }
        foreach ($arr as $ne){
            $foodOrder = new OrderFoods();
            $foodOrder->foods_id = $ne->id;
            $foodOrder->order_id = $request->order_id;
            $foodOrder->save();
        }
        return response([
            'message' => 'success',
        ], Response::HTTP_ACCEPTED);
    }
}
