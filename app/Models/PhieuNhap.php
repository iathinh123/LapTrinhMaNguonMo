<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhieuNhap extends Model
{
    use HasFactory;

    protected $table = 'PhieuNhap';
    protected $primaryKey = 'MaPN';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'NgayNhap' => 'datetime',
            'TongTienNhap' => 'decimal:2',
        ];
    }

    public function nhanVien(): BelongsTo { return $this->belongsTo(NhanVien::class, 'MaNV', 'MaNV'); }
    public function nhaCungCap(): BelongsTo { return $this->belongsTo(NhaCungCap::class, 'MaNCC', 'MaNCC')->withTrashed(); }
    public function chiTiet(): HasMany { return $this->hasMany(ChiTietPhieuNhap::class, 'MaPN', 'MaPN'); }
    public function sanPhams(): BelongsToMany
    {
        return $this->belongsToMany(SanPham::class, 'ChiTietPhieuNhap', 'MaPN', 'MaSP')->withPivot('SoLuongNhap', 'GiaNhap')->withTrashed();
    }
}
