<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChiTietHoaDon extends Model
{
    use HasFactory;

    protected $table = 'ChiTietHoaDon';
    protected $primaryKey = null; // khóa kép (xem migration), thao tác qua where()/create()
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'DonGia' => 'decimal:2',
        ];
    }

    public function hoaDon(): BelongsTo { return $this->belongsTo(HoaDon::class, 'MaHD', 'MaHD'); }
    public function sanPham(): BelongsTo { return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP')->withTrashed(); }
}
