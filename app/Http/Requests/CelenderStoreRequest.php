<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CelenderStoreRequest extends FormRequest
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
            'title' => ['required', Rule::unique('celenders')],
            'date' => ['required',
                function($attribute, $value, Closure $fail){
                    $dayofweek = date('d', strtotime($value));
                    if($dayofweek != '01'){
                        $fail("Ngày bắt đầu phải là ngày đầu tiên của tháng!");
                    }
                }],
            'fileImport' => ['required', 'mimes:xls,xlsx'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tiêu đề không được để trống!',
            'title.unique' => 'Tiêu đề đã được sử dụng!',
            'date.required' => 'Ngày bắt đầu không được để trống!',
            'fileImport.required' => 'File import không được để trống!',
            'fileImport.mimes' => 'File import không đúng định dạng!',
        ];
    }
}
