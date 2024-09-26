<?php

namespace App\Http\Requests;

use App\Rules\UniqueMultipleTable;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
                new UniqueMultipleTable(['posts', 'routers'])
            ],
            'post_catalogue_id' => 'gt:0',
            'publish' => 'gt:0',
            'follow' => 'gt:0',
            'meta_title' => 'max:60',
            'meta_keyword' => 'max:60',
            'meta_description' => 'max:160'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên bài viết không được để trống!',
            'canonical.required' => 'Canonical không được để trống!',
            'publish.gt' => 'Bạn cần chọn trạng thái hoạt động!',
            'follow.gt' => 'Bạn cần chọn điều hướng!',
            'post_catalogue_id.gt' => 'Bạn cần chọn nhóm bài viết!',
            'meta_title.max' => 'Tiêu đề SEO chỉ nên có tối đa 60 kí tự',
            'meta_keyword.max' => 'Từ khóa SEO chỉ nên có tối đa 60 kí tự',
            'meta_description.max' => 'Mô tả SEO chỉ nên có tối đa 160 kí tự',
        ];
    }
}
