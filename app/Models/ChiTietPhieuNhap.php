<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChiTietPhieuNhap extends Model
{
    use HasFactory;

    protected $table = 'ChiTietPhieuNhap';
    protected $primaryKey = null; // khóa kép (xem migration), thao tác qua where()/create()
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'GiaNhap' => 'decimal:2',
        ];
    }

    public function phieuNhap(): BelongsTo { return $this->belongsTo(PhieuNhap::class, 'MaPN', 'MaPN'); }
    public function sanPham(): BelongsTo { return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP')->withTrashed(); }
}
