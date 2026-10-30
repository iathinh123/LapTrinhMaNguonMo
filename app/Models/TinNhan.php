<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinNhan extends Model
{
    use HasFactory;

    protected $table = 'TinNhan';
    protected $primaryKey = 'MaTN';
    public $incrementing = true;
    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'ThoiGian' => 'datetime',
            'DaDoc' => 'boolean',
        ];
    }
}
