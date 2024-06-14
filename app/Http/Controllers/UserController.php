<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use SmsAero\SmsAeroMessage;
use function Symfony\Component\String\s;

/**
 * @return int verify_code
 */
function generateVerifyCode():int
{
    $verify_code = strval(rand(1,9999));
    if (strlen($verify_code) <= 3){
        for ($i = strlen($verify_code); $i < 4; $i++){
            $verify_code = '0' . $verify_code;
        }
    }
    return $verify_code;
}

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createOrUpdateUserWithVerifyCode(Request $request): JsonResponse{
        $tel = $request->tel;
        $verify_code = generateVerifyCode();
        $user = User::query()->updateOrCreate(['tel' => $tel],['verify_code' => $verify_code]);

        /*Создание сообщения с кодом для авторизации*/
        $smsAeroMessage = new SmsAeroMessage('ilyushkin.vlad@mail.ru', 'VvM_8FFC9btzO_Hkaxew3-zEIH3PwLxE');
        $response = $smsAeroMessage->send(['number' => $tel, 'text' => 'Ваш код для авторизации:' . $verify_code, 'sign' => 'Радуга цветов']);

        return response()->json([
            'success' => true,
            'message' => 'Пользователю отправлен код доступа'
        ]);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyUserAndUpdateVerifyCode(Request $request) : JsonResponse
    {
        $tel = $request->tel;
        $code = $request->code;
        $user = User::query()->where('tel',$tel)->first();
        if($user->verify_code == $code){
            $user->update(['verify_code' => generateVerifyCode()]);
            return response()->json([
                'success' => 'Авторизация прошла успешно!',
                'token' => $user->createToken('token')->plainTextToken
            ]);
        }
        return response()->json([
            'success' => false,
            'error' => 'Неправильный код'
        ],400);
    }

    public function getUserData(Request $request)
    {
        $user = $request->user;
        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }

    public function updateUserData(Request $request)
    {
        $user = $request->user;
        $user->update([
            'surname' => $request->surname,
            'name' => $request->name,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Данные обновились'
        ]);
    }
//    public function getUserCart(Request $request)
//    {
//        $user = $request->user;
//        $cart = Cart::query()->where('user_id',$user->id)->get();
//
//        if($cart){
//            return response()->json([
//                'success' => true,
//                'cart' => $cart,
//            ]);
//        }
//        return response()->json([
//            'success' => false,
//            'message' => 'У пользователя нет товаров в корзине'
//        ]);
//    }
//
//    public function getUserFavorite(Request $request)
//    {
//        $user = $request->user;
//        $favorite = Cart::query()->where('user_id',$user->id)->get();
//        if($favorite){
//            return response()->json([
//                'success' => true,
//                'cart' => $favorite,
//            ]);
//        }
//        return response()->json([
//            'success' => false,
//            'message' => 'У пользователя нет товаров в избранном'
//        ]);
//    }
}
