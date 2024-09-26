<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouseRequest extends FormRequest
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
            'name' => 'required|string',
            'code' => 'required|string|regex:/^[a-zA-Z0-9]+$/|unique:warehouses,code,'.$this->id,
            'supervisor' => 'gt:0'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên kho hàng không được để trống.',
            'name.string' => 'Tên kho hàng phải ở dạng chuỗi kí tự',
            'code.required' => 'Mã kho hàng không được để trống.',
            'code.string' => 'Mã kho hàng phải ở dạng chuỗi kí tự',
            'code.regex' => 'Mã kho hàng chỉ được chứa chữ cái và số',
            'code.unique' => 'Mã kho hàng đã tồn tại. Vui lòng chọn mã kho hàng khác',
            'supervisor.gt' => 'Bạn cần chọn người quản lý'
        ];
    }
}
