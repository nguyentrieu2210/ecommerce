<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|max:12'
        ];

    }


    public function messages()
    {
        return [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Định dạng Email không hợp lệ.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải chứa từ 6 đến 12 kí tự',
            'password.max' => 'Mật khẩu phải chứa từ 6 đến 12 kí tự',
        ];
    }
}
