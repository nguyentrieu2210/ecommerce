<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInfoRequest extends FormRequest
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
            'email' => 'unique:customers,email,'.$this->id,
        ];

    }


    public function messages()
    {
        return [
            'email.unique' => 'Email đã tồn tại, vui lòng sử dụng email khác.',
        ];
    }
}
