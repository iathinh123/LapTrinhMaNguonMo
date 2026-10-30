<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

/** Tài khoản đăng nhập (thay cho model User mặc định của Laravel). */
class TaiKhoan extends Authenticatable
{
    use HasFactory;

    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_STAFF = 'ROLE_STAFF';
    public const ROLE_CUSTOMER = 'ROLE_CUSTOMER';
    public const ADMIN_ROLES = [self::ROLE_ADMIN, self::ROLE_STAFF];

    protected $table = 'TaiKhoan';
    protected $primaryKey = 'TenDangNhap';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = ['MatKhau'];

    protected function casts(): array
    {
        return [
            'MatKhau' => 'hashed',
            'TrangThai' => 'boolean',
        ];
    }

    // Cột mật khẩu tên MatKhau; bảng không có remember_token nên tắt "ghi nhớ" của guard.
    public function getAuthPasswordName(): string { return 'MatKhau'; }
    public function getRememberTokenName(): string { return ''; }

    public function khachHang(): HasOne { return $this->hasOne(KhachHang::class, 'TenDangNhap', 'TenDangNhap'); }
    public function nhanVien(): HasOne { return $this->hasOne(NhanVien::class, 'TenDangNhap', 'TenDangNhap'); }

    public function laQuanTri(): bool { return in_array($this->Quyen, self::ADMIN_ROLES, true); }
}
