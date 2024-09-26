<?php

namespace App\Http\Requests;

use App\Rules\UniqueMultipleTable;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'required',
            'code' => 'required|unique:products,code,'.$this->id,
            'canonical' => [
                'required', 
                'max:255',
                new UniqueMultipleTable(['products', 'routers'], $this->route('id'))
            ],
            'publish' => 'gt:0',
            'follow' => 'gt:0',
            'product_catalogue_id' => 'gt:0'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên nhóm sản phẩm không được để trống!',
            'canonical.required' => 'Canonical không được để trống!',
            'code.required' => 'Mã sản phẩm không được để trống!',
            'code.unique' => 'Mã sản phẩm đã tồn tại. Vui lòng sử dụng mã khác!',
            'publish.gt' => 'Bạn cần chọn trạng thái hoạt động!',
            'follow.gt' => 'Bạn cần chọn điều hướng!',
            'product_catalogue_id.gt' => 'Bạn cần chọn nhóm sản phẩm!'
        ];
    }
}
