<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThongBao extends Model
{
    use HasFactory;

    protected $table = 'ThongBao';
    protected $primaryKey = 'MaTB';
    public $incrementing = true;
    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'NgayTao' => 'datetime',
            'TrangThai' => 'boolean',
        ];
    }

    public function khachHang(): BelongsTo { return $this->belongsTo(KhachHang::class, 'MaKH', 'MaKH'); }
}
