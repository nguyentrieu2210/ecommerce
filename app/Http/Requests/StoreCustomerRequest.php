<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the Customer is authorized to make this request.
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
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:6|max:12|confirmed',
            'name' => 'required|string|regex:/^[\pL\s]+$/u',
            'customer_catalogue_id' => 'gt:0',
            'source_id' => 'gt:0'
        ];

    }


    public function messages()
    {
        return [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Định dạng Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại, vui lòng sử dụng email khác.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải chứa tối thiểu 6 kí tự',
            'password.max' => 'Mật khẩu chỉ chứa tối đa 12 kí tự',
            'password.confirmed' => 'Mật khẩu và mật khẩu nhập lại không khớp',
            'name.required' => 'Họ tên không được để trống.',
            'name.string' => 'Họ tên phải ở dạng chuỗi kí tự',
            'name.regex' => 'Họ tên không được chứa các kí tự đặc biệt',
            'customer_catalogue_id.gt' => 'Bạn cần chọn nhóm khách hàng',
            'source_id.gt' => 'Bạn cần chọn nguồn khách',
        ];
    }
}
