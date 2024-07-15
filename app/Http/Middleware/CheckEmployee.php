<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEmployee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role->role_name == 'admin' || 
            Auth::user()->role->role_name == 'manager' ||
            Auth::user()->role->role_name == 'accountant') 
        {
            toast('Bạn không có quyền truy cập!','error','top-right');
            return redirect()->route('admin.home');
        }
        return $next($request);
    }
}
