<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

// Подключите модель пользователя

class CheckToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Token is missing'], 401);
        }

        $tokenChecked = PersonalAccessToken::findToken($token);
        $user = $tokenChecked->user;

        // Если нужно, вы можете сохранить пользователя в объекте запроса для дальнейшего использования
        $request->user = $user;

        return $next($request);
    }
}
