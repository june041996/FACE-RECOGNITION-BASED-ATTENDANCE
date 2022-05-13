<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
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
            'password' => 'required',
            'email' => 'required'  

        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Tên tài khoản không được để trống',
            'password.required' => 'Mật khẩu không được để trống',
            'email.required' => 'Địa chỉ email không được để trống'         
        ];
    }
}
