<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KhachHang extends Model
{
    use HasFactory;

    protected $table = 'KhachHang';
    protected $primaryKey = 'MaKH';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function taiKhoan(): BelongsTo { return $this->belongsTo(TaiKhoan::class, 'TenDangNhap', 'TenDangNhap'); }
    public function hoaDons(): HasMany { return $this->hasMany(HoaDon::class, 'MaKH', 'MaKH'); }
    public function gioHang(): HasMany { return $this->hasMany(ChiTietGioHang::class, 'MaKH', 'MaKH'); }
    public function binhLuans(): HasMany { return $this->hasMany(BinhLuan::class, 'MaKH', 'MaKH'); }
    public function khuyenMais(): BelongsToMany
    {
        return $this->belongsToMany(KhuyenMai::class, 'KhachHang_KhuyenMai', 'MaKH', 'MaKM')->withPivot('TrangThaiSuDung');
    }
}
