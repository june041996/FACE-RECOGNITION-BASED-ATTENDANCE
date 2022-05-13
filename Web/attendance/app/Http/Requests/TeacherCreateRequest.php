<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherCreateRequest extends FormRequest
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
            'name' => 'required',
            'phone_number' => 'required'
                 ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được để trống',
            'phone_number.required' => 'Số điện thoại không được để trống'
   
        ];
    }
}
