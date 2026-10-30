<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NhaCungCapRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'TenNCC' => ['required', 'string', 'max:100'],
            'DiaChi' => ['nullable', 'string', 'max:255'],
            'SoDienThoai' => ['nullable', 'regex:/^[0-9]{9,11}$/'],
            'Email' => ['nullable', 'email', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'TenNCC.required' => 'Vui lòng nhập tên nhà cung cấp.',
            'SoDienThoai.regex' => 'Số điện thoại không hợp lệ (9-11 chữ số).',
            'Email.email' => 'Email không hợp lệ.',
        ];
    }
}
