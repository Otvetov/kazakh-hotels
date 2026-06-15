<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotBanned
{
    /**
     * Если пользователя заблокировали во время активной сессии —
     * завершаем сессию и отправляем на страницу входа.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isBanned()) {
            Auth::guard('web')->logout();

            return redirect()->route('login')->withErrors([
                'email' => __('messages.account_banned'),
            ]);
        }

        return $next($request);
    }
}
