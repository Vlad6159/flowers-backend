<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product_to_Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use SmsAero\SmsAeroMessage;

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
        $cost = $request->price;
        $order = Order::query()->create([
            'cost' => $cost,
            'user_id' => $request->user->id
        ])->get();
        foreach ($cart as $product) {
            Product_to_Order::query()->create([
                'product_id' => $product['id'],
                'order_id' => $order['id'],
                'count' => $product['count'] ?? 1,
            ]);
        }
        $tel = $request->user->tel;
        $smsAeroMessage = new SmsAeroMessage('ilyushkin.vlad@mail.ru', 'VvM_8FFC9btzO_Hkaxew3-zEIH3PwLxE');
        $response = $smsAeroMessage->send(['number' => $tel, 'text' => 'Ваш заказ оформлен номер заказа' . $order->id, 'sign' => 'SMSAero']);
        return response()->json([
            'success' => true,
            'message' => 'Заказ оформлен'
        ]);
    }

    public function orderCheck()
    {
        return response()->json([
            'success' => true,
            'message' => 'пришло',
        ]);
    }
}
