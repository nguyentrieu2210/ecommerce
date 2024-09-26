<?php

namespace App\Http\Requests;

use App\Rules\UniqueMultipleTable;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostCatalogueRequest extends FormRequest
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
            'canonical' => [
                'required', 
                'max:255',
                new UniqueMultipleTable(['post_catalogues', 'routers'], $this->route('id'))
            ],
            'publish' => 'gt:0',
            'follow' => 'gt:0'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên nhóm bài viết không được để trống!',
            'canonical.required' => 'Canonical không được để trống!',
            'publish.gt' => 'Bạn cần chọn trạng thái hoạt động!',
            'follow.gt' => 'Bạn cần chọn điều hướng!'
        ];
    }
}
