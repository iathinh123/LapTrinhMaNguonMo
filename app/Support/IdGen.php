<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

/** Sinh mã tự tăng cho các bảng dùng khóa CHAR(n) (KH001, HD00A, DM08...). Gọi trong DB::transaction khi tạo bản ghi. */
final class IdGen
{
    /** Mã số thập phân: prefix + số có padding. Ví dụ numeric('KhachHang', 'MaKH', 'KH', 3) => KH002 */
    public static function numeric(string $table, string $col, string $prefix, int $width): string
    {
        $last = DB::table($table)->where($col, 'like', $prefix . '%')
            ->orderByRaw("LENGTH(`$col`) DESC")->orderByDesc($col)->value($col);
        $num = 0;
        if ($last !== null && ctype_digit(substr((string) $last, strlen($prefix)))) {
            $num = (int) substr((string) $last, strlen($prefix));
        }
        return $prefix . str_pad((string) ($num + 1), $width, '0', STR_PAD_LEFT);
    }

    /** Mã hệ cơ số 36 cố định 3 ký tự (HD001 ... HDZZZ), dùng cho hóa đơn. */
    public static function base36(string $table, string $col, string $prefix): string
    {
        $last = DB::table($table)->where($col, 'like', $prefix . '%')
            ->whereRaw("LENGTH(`$col`) = ?", [strlen($prefix) + 3])->orderByDesc($col)->value($col);
        $num = 0;
        if ($last !== null) {
            $part = strtoupper(substr((string) $last, strlen($prefix)));
            if (preg_match('/^[0-9A-Z]{3}$/', $part)) {
                $num = (int) base_convert($part, 36, 10);
            }
        }
        $next = ($num + 1) % 46656;
        return $prefix . strtoupper(str_pad(base_convert((string) $next, 10, 36), 3, '0', STR_PAD_LEFT));
    }
}
