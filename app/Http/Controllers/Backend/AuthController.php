<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function index () {
        return view('backend.login');
    }

    public function login (LoginUserRequest $request) {
        $account = $request->only('email', 'password');
        if(Auth::attempt($account)) {
            if(Auth::user()->publish !== 2) {
                Auth::logout();
                return redirect()->route('auth.index')->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng đăng nhập lại sau!');
            }
            return redirect()->intended()->with('success', 'Đăng nhập thành công');
        }

        return redirect()->back()->with('error', 'Email hoặc mật khẩu không đúng');
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/dashboard');
    }
}
