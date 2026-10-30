<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'maSp' => ['nullable', 'exists:SanPham,MaSP'],   // có mã = "mua ngay" một sản phẩm; trống = thanh toán cả giỏ
            'soLuong' => ['nullable', 'integer', 'min:1'],
            'diaChi' => ['required', 'string', 'max:255'],
            'sdt' => ['required', 'regex:/^[0-9]{9,11}$/'],
            'maKm' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'diaChi.required' => 'Vui lòng nhập địa chỉ nhận hàng chi tiết.',
            'sdt.required' => 'Vui lòng nhập số điện thoại nhận hàng.',
            'sdt.regex' => 'Số điện thoại không hợp lệ (phải gồm 9-11 chữ số).',
        ];
    }
}
