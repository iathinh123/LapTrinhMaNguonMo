<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'regex:/^[A-Za-z0-9._@-]{3,100}$/', 'unique:TaiKhoan,TenDangNhap'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['required', 'regex:/^[0-9]{9,11}$/', 'unique:KhachHang,DienThoai'],
            'email' => ['nullable', 'email', 'max:100', 'unique:KhachHang,Email'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.regex' => 'Tên đăng nhập chỉ gồm chữ, số, . _ @ - và dài từ 3 đến 100 ký tự.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ (9-11 chữ số).',
            'phone.unique' => 'Số điện thoại đã được sử dụng.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã được sử dụng.',
        ];
    }
}
