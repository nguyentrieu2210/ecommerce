<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWidgetRequest extends FormRequest
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
            'keyword' => 'required|unique:widgets,keyword,'.$this->id,
            'model_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên Widget không được để trống.',
            'keyword.required' => 'Từ khóa không được để trống.',
            'keyword.unique' => 'Từ khóa đã tồn tại. Vui lòng chọn từ khóa khác',
            'model_id.required' => 'Bạn cần chọn nội dung cho Widget'
        ];
    }
}

