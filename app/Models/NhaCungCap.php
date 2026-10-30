<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class NhaCungCap extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'NhaCungCap';
    protected $primaryKey = 'MaNCC';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function sanPhams(): HasMany { return $this->hasMany(SanPham::class, 'MaNCC', 'MaNCC'); }
    public function phieuNhaps(): HasMany { return $this->hasMany(PhieuNhap::class, 'MaNCC', 'MaNCC'); }
}
