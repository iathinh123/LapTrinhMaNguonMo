<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class SanPham extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'SanPham';
    protected $primaryKey = 'MaSP';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'Gia' => 'decimal:2',
            'SoLuong' => 'integer',
            'NguongToiThieu' => 'integer',
            'CreatedDate' => 'datetime',
            'LastModifiedDate' => 'datetime',
        ];
    }

    public function danhMuc(): BelongsTo { return $this->belongsTo(DanhMuc::class, 'MaDM', 'MaDM')->withTrashed(); }
    public function nhaCungCap(): BelongsTo { return $this->belongsTo(NhaCungCap::class, 'MaNCC', 'MaNCC')->withTrashed(); }
    public function binhLuans(): HasMany { return $this->hasMany(BinhLuan::class, 'MaSP', 'MaSP'); }
    public function chiTietHoaDons(): HasMany { return $this->hasMany(ChiTietHoaDon::class, 'MaSP', 'MaSP'); }

    public function conHang(): bool { return $this->SoLuong > 0; }

    /** Ảnh sản phẩm: ưu tiên file upload trong storage, sau đó thư mục public/images, cuối cùng ảnh giữ chỗ. */
    public function anhUrl(): string
    {
        if (blank($this->HinhAnh)) {
            return 'https://placehold.co/250x250?text=No+Image';
        }
        if (str_starts_with($this->HinhAnh, 'http')) {
            return $this->HinhAnh;
        }

        return Storage::disk('public')->exists('products/'.$this->HinhAnh)
            ? Storage::disk('public')->url('products/'.$this->HinhAnh)
            : asset('images/'.rawurlencode($this->HinhAnh));
    }

    /** Cập nhật cột TinhTrang theo tồn kho hiện tại. */
    public function capNhatTinhTrang(): void
    {
        $this->TinhTrang = match (true) {
            $this->SoLuong <= 0 => 'Hết hàng',
            $this->SoLuong <= $this->NguongToiThieu => 'Sắp hết',
            default => 'Còn hàng',
        };
        $this->save();
    }

    /** Tìm theo tên sản phẩm (giữ từ khóa khi phân trang bằng withQueryString()). */
    public function scopeTimKiem(Builder $q, ?string $tuKhoa): Builder
    {
        return $tuKhoa === null || $tuKhoa === '' ? $q : $q->where('TenSP', 'like', '%' . $tuKhoa . '%');
    }
}
