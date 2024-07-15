<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Closure;

class UserChangeInfoRequest extends FormRequest
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
            // 'name'=> ['required', Rule::unique('employees')->ignore(Auth()->user()->id)],
            // 'phone'=> ['required', 'numeric', Rule::unique('employees')->ignore(Auth()->user()->id),
            //     function($attribute, $value, Closure $fail){
            //         $pattern='/^0[0-9]*$/';
            //         if(!preg_match($pattern,$value)){
            //             $fail("Số điện thoại phải là số và bắt đầu bằng 0!");
            //         }
            //     }
            // ],
        ];
    }

    public function messages()
    {
        return [
            // 'name.required' => 'Tên không được để trống!',
            // 'name.unique' => 'Tên đã được sử dụng!',

            // 'phone.unique' => 'Số điện thoại đã được sử dụng!',
            // 'phone.required' => 'Số điện thoại không được để trống!',
            // 'phone.numeric' => 'Số điện thoại không đúng định dạng!',
        ];
    }
}
