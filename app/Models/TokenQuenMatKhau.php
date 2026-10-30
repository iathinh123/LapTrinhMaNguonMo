<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TokenQuenMatKhau extends Model
{
    use HasFactory;

    protected $table = 'TokenQuenMatKhau';
    protected $primaryKey = 'MaToken';
    public $incrementing = true;
    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'NgayTao' => 'datetime',
            'NgayHetHan' => 'datetime',
        ];
    }

    public function taiKhoan(): BelongsTo { return $this->belongsTo(TaiKhoan::class, 'TenDangNhap', 'TenDangNhap'); }
}
