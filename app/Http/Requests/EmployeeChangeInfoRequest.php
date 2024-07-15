<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Closure;

class EmployeeChangeInfoRequest extends FormRequest
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
        $roles_id = Role::where('role_name', '!=', 'admin')
                        ->where('role_name', '!=', 'manager')
                        ->where('role_name', '!=', 'accountant')->pluck('id')->toArray();
        return [
            'name' => ['required','min:5', 'max:100'],
            'phone' => ['required', 'numeric', Rule::unique('employees')->ignore(Auth()->user()->id),
                function($attribute, $value, Closure $fail){
                    $pattern='/^0[0-9]*$/';
                    if(!preg_match($pattern,$value)){
                        $fail("Số điện thoại phải là số và bắt đầu bằng 0!");
                    }
                }
            ],
            'code' => ['required', Rule::unique('employees')->ignore(Auth()->user()->id)],
            'address' => ['required'],
            'home_town' => ['required'],
            'gender' => ['required'],
            'birthday' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'CCCD' => ['required', Rule::unique('employees')->ignore(Auth()->user()->id)],
            'marital_status' => ['required'],
            'date_joining' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được để trống!',
            'name.min' => 'Tên phải có ít nhất 5 kí tự!',
            'name.max' => 'Tên tối đa 100 kí tự!',

            'phone.unique' => 'Số điện thoại đã được sử dụng!',
            'phone.required' => 'Số điện thoại không được để trống!',
            'phone.numeric' => 'Số điện thoại không đúng định dạng!',

            'code.required' => 'Mã nhân viên không được để trống!',
            'code.unique' => 'Mã nhân viên đã được sử dụng',

            'address.required' => 'Địa chỉ không được để trống!',

            'home_town.required' => 'Quê quán không được để trống!',

            'gender.required' => 'Giới tính không được để trống!',

            'birthday.required' => 'Ngày sinh không được để trống!',
            'birthday.date' => 'Ngày sinh chưa đúng định dạng!',
            'birthday.before' => 'Ngày sinh không hợp lệ!',
            'birthday.after' => 'Ngày sinh không hợp lệ!',
            
            'CCCD.required' => 'Số CCCD không được để trống!',
            'CCCD.unique' => 'Số CCCD đã được sử dụng!',

            'marital_status.required' => 'Tình trạng hôn nhân không được để trống!',
            
            'date_joining.required' => 'Ngày vào công ty không được để trống!',
        ];
    }
}
