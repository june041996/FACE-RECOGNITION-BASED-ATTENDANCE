<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentEditRequest extends FormRequest
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
            'username' => 'required',
            'mssv' => 'required|unique:student',
            'password' => 'required'
            
        ];
    }
    public function messages()
    {
        return [
            'username.required' => 'Tên không được để trống',
            'mssv.unique' => 'Mã số sinh viên bị trùng',
            'mssv.required' => 'Mã số sinh viên không được để trống',
            'password.required' => 'Mật khẩu không được để trống'
   
        ];
    }
}
