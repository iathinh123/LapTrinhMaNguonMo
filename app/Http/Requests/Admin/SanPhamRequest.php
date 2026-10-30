<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SanPhamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'TenSP' => ['required', 'string', 'max:150'],
            'MaDM' => ['required', 'exists:DanhMuc,MaDM'],
            'MaNCC' => ['required', 'exists:NhaCungCap,MaNCC'],
            'Gia' => ['required', 'numeric', 'min:0'],
            'SoLuong' => ['required', 'integer', 'min:0'],
            'NguongToiThieu' => ['required', 'integer', 'min:0'],
            'MoTa' => ['nullable', 'string', 'max:255'],
            'HinhAnh' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'TenSP.required' => 'Vui lòng nhập tên sản phẩm.',
            'MaDM.required' => 'Vui lòng chọn danh mục.',
            'MaNCC.required' => 'Vui lòng chọn nhà cung cấp.',
            'Gia.required' => 'Vui lòng nhập giá.',
            'Gia.min' => 'Giá không được âm.',
            'SoLuong.min' => 'Số lượng không được âm.',
            'HinhAnh.image' => 'File tải lên phải là ảnh.',
            'HinhAnh.mimes' => 'Ảnh chỉ nhận định dạng jpg, png, webp.',
            'HinhAnh.max' => 'Ảnh tối đa 2MB.',
        ];
    }
}
