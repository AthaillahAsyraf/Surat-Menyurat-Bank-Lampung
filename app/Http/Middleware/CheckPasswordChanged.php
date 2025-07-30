<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPasswordChanged
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->password_changed) {
            if (!$request->routeIs('profile.*', 'logout')) {
                return redirect()->route('profile.edit')
                    ->with('warning', 'Anda harus mengubah password default terlebih dahulu.');
            }
        }

        return $next($request);
    }
}