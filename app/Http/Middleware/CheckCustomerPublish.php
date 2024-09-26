<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCustomerPublish
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::guard('customer')->check()) {
            if(Auth::guard('customer')->user()->publish !== 2) {
                Auth::guard('customer')->logout();
                return redirect()->back()->with('error', 'Tài khoản của bạn đã bị khóa. Hãy thử truy cập lại sau!');
            }
        }
        return $next($request);
    }
}
