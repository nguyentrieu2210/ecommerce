<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSlideRequest extends FormRequest
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
            'keyword' => 'required|unique:slides',
            'item.image' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên Slide không được để trống.',
            'keyword.required' => 'Từ khóa không được để trống.',
            'keyword.unique' => 'Từ khóa đã tồn tại. Vui lòng chọn từ khóa khác',
            'item.image.required' => 'Bạn cần chọn ít nhất 1 ảnh'
        ];
    }
}

