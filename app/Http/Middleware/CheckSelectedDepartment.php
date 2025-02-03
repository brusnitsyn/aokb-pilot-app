<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSelectedDepartment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Получаем значение cookie
        $myCookie = $request->cookie('activeDepartment');

        // Проверяем значение cookie
        if ($myCookie != null) {
            // Если значение cookie соответствует ожидаемому, продолжаем запрос
            return $next($request);
        }

        // Если значение cookie не соответствует ожидаемому, перенаправляем на другую страницу
        return redirect('/workspace');
    }
}
