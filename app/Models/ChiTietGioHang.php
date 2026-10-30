<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChiTietGioHang extends Model
{
    use HasFactory;

    protected $table = 'ChiTietGioHang';
    protected $primaryKey = null; // khóa kép (xem migration), thao tác qua where()/create()
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'NgayThem' => 'datetime',
        ];
    }

    public function khachHang(): BelongsTo { return $this->belongsTo(KhachHang::class, 'MaKH', 'MaKH'); }
    public function sanPham(): BelongsTo { return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP'); }
}
