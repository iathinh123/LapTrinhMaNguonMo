<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HoSoController extends Controller
{
    public function show(Request $request): View
    {
        return view('customers.profile', ['kh' => $this->khachHang($request), 'tk' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $kh = $this->khachHang($request);
        $d = $request->validate([
            'hoTen' => ['required', 'string', 'max:100'],
            'gioiTinh' => ['nullable', 'in:Nam,Nữ,Khác'],
            'diaChi' => ['nullable', 'string', 'max:255'],
            'dienThoai' => ['required', 'regex:/^[0-9]{9,11}$/', Rule::unique('KhachHang', 'DienThoai')->ignore($kh->MaKH, 'MaKH')],
            'email' => ['nullable', 'email', 'max:100', Rule::unique('KhachHang', 'Email')->ignore($kh->MaKH, 'MaKH')],
        ], [
            'hoTen.required' => 'Họ tên không được để trống.',
            'dienThoai.regex' => 'Số điện thoại không hợp lệ (9-11 chữ số).',
            'dienThoai.unique' => 'Số điện thoại đã được sử dụng bởi tài khoản khác.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã được sử dụng bởi tài khoản khác.',
        ]);

        $kh->update([
            'HoTen' => $d['hoTen'], 'GioiTinh' => $d['gioiTinh'] ?? null, 'DiaChi' => $d['diaChi'] ?? null,
            'DienThoai' => $d['dienThoai'], 'Email' => $d['email'] ?: null,
        ]);

        return redirect()->route('profile.show')->with('success', 'Cập nhật thông tin hồ sơ thành công!');
    }

    public function doiMatKhau(Request $request): RedirectResponse
    {
        /** @var TaiKhoan $tk */
        $tk = $request->user();
        abort_if($tk->LoaiTaiKhoan === 'GOOGLE', 403, 'Tài khoản đăng nhập bằng Google không thể thay đổi mật khẩu.');

        $request->validate([
            'oldPassword' => ['required', 'current_password'],
            'newPassword' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'oldPassword.current_password' => 'Mật khẩu hiện tại không chính xác.',
            'newPassword.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'newPassword.confirmed' => 'Mật khẩu mới và xác nhận mật khẩu không khớp.',
        ]);

        $tk->update(['MatKhau' => $request->input('newPassword')]);

        return redirect()->route('profile.show')->with('success', 'Đổi mật khẩu thành công!');
    }
}
