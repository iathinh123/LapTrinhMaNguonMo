<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NhanVien extends Model
{
    use HasFactory;

    protected $table = 'NhanVien';
    protected $primaryKey = 'MaNV';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function taiKhoan(): BelongsTo { return $this->belongsTo(TaiKhoan::class, 'TenDangNhap', 'TenDangNhap'); }
    public function hoaDons(): HasMany { return $this->hasMany(HoaDon::class, 'MaNV', 'MaNV'); }
    public function phieuNhaps(): HasMany { return $this->hasMany(PhieuNhap::class, 'MaNV', 'MaNV'); }
}
