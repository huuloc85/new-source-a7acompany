<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleUpdateRequest extends FormRequest
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
            'role_name' => ['required', Rule::unique('roles')->ignore($this->id)],
        ];
    }

    public function messages()
    {
        return [
            'role_name.required' => 'Tên chức vụ không được để trống!',
            'role_name.unique' => 'Tên chức vụ đã được sử dụng!',
        ];
    }
}
