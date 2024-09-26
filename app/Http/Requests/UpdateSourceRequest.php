<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSourceRequest extends FormRequest
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
            'keyword' => 'required|string|regex:/^[\pL\s]+$/u|unique:sources,keyword,'.$this->id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên nhóm không được để trống.',
            'name.string' => 'Tên nhóm phải ở dạng chuỗi kí tự',
            'name.regex' => 'Tên nhóm không được chứa các kí tự đặc biệt',
            'keyword.required' => 'Từ khóa không được để trống.',
            'keyword.string' => 'Từ khóa phải ở dạng chuỗi kí tự',
            'keyword.regex' => 'Từ khóa không được chứa các kí tự đặc biệt',
            'keyword.unique' => 'Từ khóa đã tồn tại. Vui lòng chọn từ khóa khác'
        ];
    }
}
