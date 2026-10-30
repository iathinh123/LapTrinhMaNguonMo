<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\TaiKhoan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('customers.auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $ok = Auth::attempt([
            'TenDangNhap' => $request->string('username')->toString(),
            'password' => $request->string('password')->toString(),
            'TrangThai' => true, // tài khoản bị khóa hoặc chưa kích hoạt OTP thì không đăng nhập được
        ]);

        if (! $ok) {
            return back()->withInput($request->only('username'))
                ->withErrors(['username' => 'Tên đăng nhập hoặc mật khẩu không đúng, hoặc tài khoản đã bị khóa/chưa kích hoạt.']);
        }

        $request->session()->regenerate();
        $laQuanTri = in_array(Auth::user()->Quyen, TaiKhoan::ADMIN_ROLES, true);

        return $laQuanTri ? redirect()->intended(route('admin.dashboard')) : redirect()->intended(route('home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất thành công.');
    }
}
