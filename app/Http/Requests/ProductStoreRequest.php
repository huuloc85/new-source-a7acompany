<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
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
            'name' => ['required', Rule::unique('products')],
            'code' => ['required', Rule::unique('products')],
            // 'quantityCaTon' => ['required'],
            'moldSize' => ['required'],
            'CAV' => ['required'],
            'cycle' => ['required'],
            // 'planTime' => ['required'],
            // 'realTime' => ['required'],
            'binCode' => ['required'],
            'quanEntityBin' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên linh kiện không được để trống!',
            'name.unique' => 'Tên linh kiện đã được sử dụng!',
            'code.required' => 'Mã linh kiện không được để trống!',
            'code.unique' => 'Mã linh kiện đã được sử dụng!',
            'quantity.required' => 'Sản lượng không được để trống!',
            // 'quantityCaTon.required' => 'Số thùng caton không được để trống!',
            'moldSize.required' => 'Kích thước khuôn không được để trống!',
            'CAV.required' => 'Số cái/shot không được để trống!',
            'cycle.required' => 'Chu kì s/shot không được để trống!',
            // 'planTime.required' => 'Không được để trống!',
            // 'realTime.required' => 'Không được để trống!',
            'binCode.required' => 'Mã thùng không được để trống!',
            'quanEntityBin.required' => 'Số lượng con/thùng không được để trống!',
        ];
    }
}
