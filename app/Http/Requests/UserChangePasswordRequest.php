<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'password' => ['required'],
            'newpassword' => ['required'],
            'renewpassword' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Mật khẩu không được để trống!',
            'newpassword.required' => 'Mật khẩu mới không được để trống!',
            'renewpassword.required' => 'Bạn phải nhập lại mật khẩu mới!',
        ];
    }
}
