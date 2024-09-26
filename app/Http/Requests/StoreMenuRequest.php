<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
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
            'keyword' => 'required|unique:menus',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên Menu không được để trống.',
            'keyword.required' => 'Từ khóa không được để trống.',
            'keyword.unique' => 'Từ khóa đã tồn tại. Vui lòng chọn từ khóa khác',
        ];
    }
}

