<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:customers,email,'.$this->id,
            'name' => 'required|string|regex:/^[\pL\s]+$/u',
            'customer_catalogue_id' => 'gt:0'
        ];

    }


    public function messages()
    {
        return [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Định dạng Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại, vui lòng sử dụng email khác.',
            'name.required' => 'Họ tên không được để trống.',
            'name.string' => 'Họ tên phải ở dạng chuỗi kí tự',
            'name.regex' => 'Họ tên không được chứa các kí tự đặc biệt',
            'customer_catalogue_id.gt' => 'Bạn cần chọn nhóm khách hàng',
        ];
    }
}
