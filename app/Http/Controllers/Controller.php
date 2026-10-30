<?php

namespace App\Http\Controllers;

use App\Models\KhachHang;
use Illuminate\Http\Request;

abstract class Controller
{
    /** Hồ sơ khách hàng của tài khoản đang đăng nhập; tài khoản quản trị không có hồ sơ này nên bị chặn. */
    protected function khachHang(Request $request): KhachHang
    {
        return $request->user()->khachHang ?? abort(403, 'Không tìm thấy hồ sơ khách hàng.');
    }
}
