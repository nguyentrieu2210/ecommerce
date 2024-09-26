<?php

namespace App\Http\Controllers\Frontend;

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller
{
    // Redirect to Facebook
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    // Handle callback from Facebook
    public function handleFacebookCallback()
    {
        $this->handleProviderCallback('facebook');
    }

    // Redirect to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle callback from Google
    public function handleGoogleCallback()
    {
        $this->handleProviderCallback('google');
    }

    // Handle provider callback logic
    protected function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            $customer = Customer::where('email', $socialUser->getEmail())->first();

            if ($customer) {
                // Kiểm tra xem người dùng có đã liên kết với provider này chưa
                if (!$customer->provider || !$customer->provider_id) {
                    $customer->update([
                        'provider_id' => $socialUser->getId(),
                        'provider' => $provider,
                    ]);
                }
            } else {
                // Nếu người dùng chưa có tài khoản, tạo tài khoản mới
                $customer = Customer::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'provider_id' => $socialUser->getId(),
                    'provider' => $provider,
                ]);
            }

            Auth::guard('customer')->login($customer);

            return redirect()->intended('/');
        } catch (\Exception $e) {
            return redirect('/dang-nhap.html')->withErrors(['login' => 'Đăng nhập không thành công, hãy thử lại.']);
        }
    }

    public function deleteAccount () {
        
    }
}
