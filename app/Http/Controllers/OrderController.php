<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product_to_Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function makeOrder(Request $request)
    {
        $cart = $request->cart;
        if(!$cart){
            return response()->json([
                'success' => false,
                'message' => 'Корзина пуста, чтобы оформить заказ нужно добавить товаров.'
            ]);
        }
        $order = Order::query()->create()->get();
        $cost = 0;
        foreach ($cart as $product) {
            Product_to_Order::query()->create([
                'product_id' => $product['id'],
                'order_id' => $order['id'],
                'count' => $product['count'],
            ]);
            $cost += $product['count'] * $product['price'];
        }
        $order->update(['cost' => $cost]);
        return response()->json([
            'success' => true,
            'message' => 'Заказ оформлен'
        ]);
    }
}
