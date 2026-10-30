<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Quên mật khẩu qua OTP, đồng thời xác thực OTP khi đăng ký. */
class PasswordResetController extends Controller
{
    public function __construct(private AuthService $auth) {}

    public function forgotForm(): View
    {
        return view('customers.auth.forgot-password');
    }

    public function forgot(ForgotPasswordRequest $request): RedirectResponse
    {
        $phone = $request->validated('phone');
        $r = $this->auth->guiOtpQuenMatKhau($phone);

        return redirect()->route('otp.form', ['token' => $r['token'], 'type' => 'forgot'])
            ->with('info', $this->auth->thongBaoOtp($phone, $r));
    }

    public function otpForm(Request $request): View|RedirectResponse
    {
        $token = (string) $request->query('token');
        if ($token === '') {
            return redirect()->route('login');
        }

        return view('customers.auth.verify-otp', ['token' => $token, 'type' => (string) $request->query('type')]);
    }

    public function verifyOtp(VerifyOtpRequest $request): RedirectResponse
    {
        ['token' => $token, 'otp' => $otp] = $request->validated();
        $type = $request->input('type');

        if (! $this->auth->xacMinhOtp($token, $otp)) {
            return redirect()->route('otp.form', array_filter(['token' => $token, 'type' => $type]))
                ->withErrors(['otp' => 'Mã OTP không đúng hoặc đã hết hạn']);
        }

        if ($type === 'register') {
            $this->auth->kichHoat($token);

            return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
        }

        $request->session()->put("otp_ok.$token", true); // chỉ cho đặt lại mật khẩu sau khi đã nhập đúng OTP

        return redirect()->route('password.reset', ['token' => $token]);
    }

    public function resetForm(Request $request): View|RedirectResponse
    {
        $token = (string) $request->query('token');
        if (! $request->session()->get("otp_ok.$token") || ! $this->auth->tokenConHan($token)) {
            return redirect()->route('password.forgot')
                ->withErrors(['phone' => 'Phiên đặt lại mật khẩu không hợp lệ hoặc đã hết hạn. Vui lòng thực hiện lại.']);
        }

        return view('customers.auth.reset-password', ['token' => $token]);
    }

    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $token = $request->validated('token');
        if (! $request->session()->get("otp_ok.$token")) {
            return redirect()->route('password.forgot')->withErrors(['phone' => 'Bạn chưa xác minh OTP.']);
        }

        $this->auth->datLaiMatKhau($token, $request->validated('password'));
        $request->session()->forget("otp_ok.$token");

        return redirect()->route('login')->with('success', 'Đổi mật khẩu thành công. Vui lòng đăng nhập.');
    }
}
