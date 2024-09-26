<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            'name' => 'required',
            'code' => 'required|unique:suppliers,code,'.$this->id,
            'email' => 'nullable|email'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên nhà cung cấp không được để trống.',
            'code.required' => 'Mã nhà cung cấp không được để trống.',
            'code.unique' => 'Mã nhà cung cấp đã tồn tại. Vui lòng chọn mã khác',
            'email' => 'Email không đúng định dạng.'
        ];
    }
}
