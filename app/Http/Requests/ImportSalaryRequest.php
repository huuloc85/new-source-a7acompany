<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportSalaryRequest extends FormRequest
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
            'title' => ['required', Rule::unique('salaries_manager')],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'file_vvp' => ['required', 'mimes:xlsx, xls'],
            'file_a7a' => ['required', 'mimes:xlsx, xls'],
            'file_parttime' => ['mimes:xlsx, xls'],
            'start_date_parttime' => [function($attribute, $value, Closure $fail){
                if ($this->hasFile('file_parttime')) {
                    if ($value == null || $value == '') {
                        $fail("Bạn phải chọn ngày bắt đầu cho file thời vụ!");
                    }
                }
            }],
            'end_date_parttime' => [function($attribute, $value, Closure $fail){
                if ($this->hasFile('file_parttime')) {
                    if ($value == null || $value == '') {
                        $fail("Bạn phải chọn kết thúc đầu cho file thời vụ!");
                    }
                }
            }],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tiêu đề không được để trống!',
            'title.unique' => 'Tiêu đề đã được sử dụng!',
            'start_date.required' => 'Ngày bắt đầu không được để trống!',
            'end_date.required' => 'Ngày kết thúc không được để trống!',
            'file_vvp.required' => 'File không được để trống!',
            'file_vvp.mimes' => 'File không đúng định dạng!',
            'file_a7a.required' => 'File không được để trống!',
            'file_a7a.mimes' => 'File không đúng định dạng!',
            'file_parttime.mimes' => 'File không đúng định dạng!',
        ];
    }
}
