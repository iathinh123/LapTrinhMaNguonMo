<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    /** @return bool true nếu gửi thật qua nhà cung cấp, false nếu chỉ mô phỏng (ghi log) */
    public function guiOtp(string $dienThoai, string $otp): bool
    {
        $noiDung = "Ma OTP xac thuc cua ban la: $otp. Co hieu luc trong 5 phut.";

        if (! $this->laMock()) {
            return $this->guiQuaSpeedSms($this->soNoiDia($dienThoai), $noiDung);
        }

        $this->ghiLog("SIMULATED SMS OTP TO: $dienThoai | MESSAGE: $noiDung");

        return false;
    }

    public function laMock(): bool
    {
        return ! (config('services.sms.provider') === 'speedsms' && filled(config('services.sms.speedsms_token')));
    }

    private function guiQuaSpeedSms(string $dienThoai, string $noiDung): bool
    {
        $res = Http::withBasicAuth((string) config('services.sms.speedsms_token'), 'x')
            ->post('https://api.speedsms.vn/index.php/sms/send', [
                'to' => [$dienThoai], 'content' => $noiDung, 'sms_type' => 4,
            ]);
        $this->ghiLog("SPEEDSMS to $dienThoai => HTTP {$res->status()} {$res->body()}");

        return $res->successful();
    }

    /** SpeedSMS dùng định dạng nội địa 0xxxxxxxxx */
    public function soNoiDia(string $dienThoai): string
    {
        $p = preg_replace('/\s+/', '', trim($dienThoai));
        if (str_starts_with($p, '+84')) {
            return '0'.substr($p, 3);
        }
        if (str_starts_with($p, '84') && strlen($p) > 9) {
            return '0'.substr($p, 2);
        }

        return str_starts_with($p, '0') ? $p : '0'.$p;
    }

    private function ghiLog(string $dong): void
    {
        file_put_contents(storage_path('logs/sms.log'), '['.now()->toDateTimeString().'] '.$dong.PHP_EOL, FILE_APPEND);
    }
}
