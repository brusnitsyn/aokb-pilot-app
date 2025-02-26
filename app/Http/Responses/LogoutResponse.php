<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request): \Illuminate\Foundation\Application|JsonResponse|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|Response
    {
        if ($request->wantsJson()) {
            return new JsonResponse('', 204);
        }

//        foreach ($request->cookies as $name => $value) {
//            Cookie::queue(Cookie::forget($name));
//        }
        Cookie::queue(Cookie::forget('myDepartment'));
        Cookie::queue(Cookie::forget('activeDepartment'));

        return redirect('/');
    }
}
