<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HoaDon extends Model
{
    use HasFactory;

    protected $table = 'HoaDon';
    protected $primaryKey = 'MaHD';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'NgayLap' => 'datetime',
            'NgayThanhToan' => 'datetime',
            'TongTien' => 'decimal:2',
            'TienGiamGia' => 'decimal:2',
        ];
    }

    public const ST_DA_GIAO = 'Đã giao';
    public const ST_DA_HUY = 'Đã hủy';
    public const ST_COD = 'Chờ giao hàng (Thanh toán khi nhận hàng)';

    /** Đơn còn ở trạng thái "Chờ…" hoặc "Chưa giao" thì khách được hủy. */
    public function coTheHuy(): bool
    {
        return $this->TrangThai === 'Chưa giao' || str_starts_with((string) $this->TrangThai, 'Chờ');
    }

    public function khachHang(): BelongsTo { return $this->belongsTo(KhachHang::class, 'MaKH', 'MaKH'); }
    public function nhanVien(): BelongsTo { return $this->belongsTo(NhanVien::class, 'MaNV', 'MaNV'); }
    public function khuyenMai(): BelongsTo { return $this->belongsTo(KhuyenMai::class, 'MaKM', 'MaKM')->withTrashed(); }
    public function chiTiet(): HasMany { return $this->hasMany(ChiTietHoaDon::class, 'MaHD', 'MaHD'); }
    public function sanPhams(): BelongsToMany
    {
        return $this->belongsToMany(SanPham::class, 'ChiTietHoaDon', 'MaHD', 'MaSP')->withPivot('SoLuong', 'DonGia')->withTrashed();
    }
}
