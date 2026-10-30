<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class KhuyenMai extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'KhuyenMai';
    protected $primaryKey = 'MaKM';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'NgayBatDau' => 'datetime',
            'NgayKetThuc' => 'datetime',
            'TrangThai' => 'boolean',
        ];
    }

    /** Tìm voucher theo mã nhập (MaCode) hoặc MaKM; ném ValidationException nếu không dùng được cho đơn có giá trị $tongTien. */
    public static function hopLe(string $ma, float $tongTien): self
    {
        $ma = trim($ma);
        $km = self::where('MaCode', $ma)->orWhere('MaKM', $ma)->first();

        $loi = match (true) {
            ! $km => 'Mã khuyến mãi không tồn tại',
            ! $km->TrangThai => 'Mã khuyến mãi đã bị vô hiệu hóa',
            $km->SoLuong <= 0 => 'Mã khuyến mãi đã hết lượt sử dụng',
            $km->NgayBatDau?->isFuture() => 'Mã khuyến mãi chưa đến thời gian sử dụng',
            $km->NgayKetThuc?->isPast() => 'Mã khuyến mãi đã hết hạn',
            $tongTien < (float) $km->DonToiThieu => 'Đơn hàng chưa đạt giá trị tối thiểu để sử dụng mã này',
            default => null,
        };
        if ($loi) {
            throw ValidationException::withMessages(['maKm' => $loi]);
        }

        return $km;
    }

    public function soTienGiam(float $tongTien): float
    {
        $giam = $tongTien * $this->PhanTramGiam / 100;
        if ($this->TienGiamToiDa > 0) {
            $giam = min($giam, (float) $this->TienGiamToiDa);
        }

        return round(min($giam, $tongTien));
    }

    public function hoaDons(): HasMany { return $this->hasMany(HoaDon::class, 'MaKM', 'MaKM'); }
    public function khachHangs(): BelongsToMany
    {
        return $this->belongsToMany(KhachHang::class, 'KhachHang_KhuyenMai', 'MaKM', 'MaKH')->withPivot('TrangThaiSuDung');
    }
}
