<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DanhMucRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['TenDM' => ['required', 'string', 'max:100']];
    }

    public function messages(): array
    {
        return ['TenDM.required' => 'Vui lòng nhập tên danh mục.'];
    }
}
