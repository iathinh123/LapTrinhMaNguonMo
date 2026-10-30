<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BinhLuan extends Model
{
    use HasFactory;

    protected $table = 'BinhLuan';
    protected $primaryKey = 'MaBL';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'NgayDang' => 'datetime',
            'TrangThai' => 'boolean',
        ];
    }

    public function sanPham(): BelongsTo { return $this->belongsTo(SanPham::class, 'MaSP', 'MaSP')->withTrashed(); }
    public function khachHang(): BelongsTo { return $this->belongsTo(KhachHang::class, 'MaKH', 'MaKH'); }
}
