<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerCatalogueRequest extends FormRequest
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
            'name' => 'required|string|regex:/^[\pL\s]+$/u',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên nhóm không được để trống.',
            'name.string' => 'Tên nhóm phải ở dạng chuỗi kí tự',
            'name.regex' => 'Tên nhóm không được chứa các kí tự đặc biệt',
        ];
    }
}
