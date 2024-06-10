<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User; // Подключите модель пользователя

class CheckToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Token is missing'], 401);
        }

        $user = User::tokens()->where('token', $token)->firs t();

        if (!$user) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Если нужно, вы можете сохранить пользователя в объекте запроса для дальнейшего использования
        $request->user = $user;

        return $next($request);
    }
}
