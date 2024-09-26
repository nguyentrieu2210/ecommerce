<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
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
            'canonical' => 'required|max:255|unique:permissions,canonical,'.$this->id,
            'name' => 'required|string|regex:/^[\pL\s]+$/u',
        ];

    }


    public function messages()
    {
        return [
            'canonical.required' => 'Canonical không được để trống.',
            'canonical.unique' => 'Email đã tồn tại, vui lòng sử dụng email khác.',
            'name.required' => 'Họ tên không được để trống.',
            'name.string' => 'Họ tên phải ở dạng chuỗi kí tự',
            'name.regex' => 'Họ tên không được chứa các kí tự đặc biệt',
        ];
    }
}
