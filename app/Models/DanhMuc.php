<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DanhMuc extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'DanhMuc';
    protected $primaryKey = 'MaDM';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function sanPhams(): HasMany { return $this->hasMany(SanPham::class, 'MaDM', 'MaDM'); }
}
