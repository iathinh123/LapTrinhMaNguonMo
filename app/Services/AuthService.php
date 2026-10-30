<?php

namespace App\Services;

use App\Mail\OtpMatKhauMail;
use App\Models\KhachHang;
use App\Models\TaiKhoan;
use App\Models\TokenQuenMatKhau;
use App\Support\IdGen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    private const OTP_PHUT = 5;

    public function __construct(private SmsService $sms) {}

    /**
     * Đăng ký: tạo tài khoản (chưa kích hoạt) + hồ sơ khách hàng + OTP.
     *
     * @param  array{username:string,password:string,phone:string,email:?string}  $d
     * @return array{token:string,otp:string,sent:bool}
     */
    public function dangKy(array $d): array
    {
        $otp = $this->taoOtp();
        $token = (string) Str::uuid();

        DB::transaction(function () use ($d, $otp, $token) {
            TaiKhoan::create([
                'TenDangNhap' => $d['username'], 'MatKhau' => $d['password'], 'Quyen' => TaiKhoan::ROLE_CUSTOMER,
                'TrangThai' => false, 'LoaiTaiKhoan' => 'LOCAL', // chưa kích hoạt đến khi xác thực OTP
            ]);
            KhachHang::create([
                'MaKH' => IdGen::numeric('KhachHang', 'MaKH', 'KH', 3), 'HoTen' => $d['username'],
                'DienThoai' => $d['phone'], 'Email' => $d['email'] ?: null, 'TenDangNhap' => $d['username'],
            ]);
            $this->luuToken($d['username'], $token, $otp);
        });

        return ['token' => $token, 'otp' => $otp, 'sent' => $this->sms->guiOtp($d['phone'], $otp)];
    }

    /** Quên mật khẩu: sinh OTP theo số điện thoại. @return array{token:string,otp:string,sent:bool} */
    public function guiOtpQuenMatKhau(string $dienThoai): array
    {
        $dienThoai = trim($dienThoai);
        $kh = KhachHang::where('DienThoai', $dienThoai)->first();
        if (! $kh || empty($kh->TenDangNhap)) {
            throw ValidationException::withMessages(['phone' => 'Số điện thoại không tồn tại']);
        }

        $otp = $this->taoOtp();
        $token = (string) Str::uuid();
        $this->luuToken($kh->TenDangNhap, $token, $otp);

        if (filled($kh->Email)) {
            try {
                Mail::to($kh->Email)->send(new OtpMatKhauMail($otp, self::OTP_PHUT));
            } catch (\Throwable $e) {
                report($e); // lỗi gửi mail không chặn luồng OTP qua SMS
            }
        }

        return ['token' => $token, 'otp' => $otp, 'sent' => $this->sms->guiOtp($dienThoai, $otp)];
    }

    public function xacMinhOtp(string $token, string $otp): bool
    {
        $t = TokenQuenMatKhau::where('ChuoiToken', $token)->first();

        return $t !== null && $t->OTPCode !== null && hash_equals($t->OTPCode, $otp) && $t->NgayHetHan->isFuture();
    }

    public function kichHoat(string $token): void
    {
        $t = $this->layTokenConHan($token);
        TaiKhoan::where('TenDangNhap', $t->TenDangNhap)->update(['TrangThai' => true]);
        $t->delete();
    }

    public function tokenConHan(string $token): bool
    {
        $t = TokenQuenMatKhau::where('ChuoiToken', $token)->first();

        return $t !== null && $t->NgayHetHan->isFuture();
    }

    public function datLaiMatKhau(string $token, string $matKhauMoi): void
    {
        $t = $this->layTokenConHan($token);
        TaiKhoan::findOrFail($t->TenDangNhap)->update(['MatKhau' => $matKhauMoi]);
        $t->delete();
    }

    /** Thông báo hiển thị sau khi gửi OTP; ở chế độ demo (SMS mock) in luôn mã OTP lên màn hình. */
    public function thongBaoOtp(string $dienThoai, array $r): string
    {
        $msg = 'Mã OTP đã được gửi đến số điện thoại '.$dienThoai;
        if (! $r['sent'] && $this->sms->laMock() && config('services.sms.demo_show_otp')) {
            $msg .= ' (CHẾ ĐỘ DEMO – chưa cấu hình SMS, mã OTP của bạn là: '.$r['otp'].')';
        }

        return $msg;
    }

    private function layTokenConHan(string $token): TokenQuenMatKhau
    {
        $t = TokenQuenMatKhau::where('ChuoiToken', $token)->first();
        if (! $t) {
            throw ValidationException::withMessages(['token' => 'Mã xác thực không hợp lệ']);
        }
        if ($t->NgayHetHan->isPast()) {
            throw ValidationException::withMessages(['token' => 'Mã xác thực đã hết hạn']);
        }

        return $t;
    }

    private function luuToken(string $tenDangNhap, string $token, string $otp): void
    {
        TokenQuenMatKhau::create([
            'TenDangNhap' => $tenDangNhap, 'ChuoiToken' => $token, 'OTPCode' => $otp,
            'NgayTao' => now(), 'NgayHetHan' => now()->addMinutes(self::OTP_PHUT),
        ]);
    }

    private function taoOtp(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
