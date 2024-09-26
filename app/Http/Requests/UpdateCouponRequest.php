<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
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
            'code' => 'required|unique:coupons,code,'.$this->id,
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Mã giảm giá không được để trống.',
            'code.unique' => 'Mã giảm giá đã tồn tại. Vui lòng chọn từ khóa khác'
        ];
    }
}
