<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KhuyenMaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $hienTai = $this->route('khuyenmai')?->MaKM;

        return [
            'MaCode' => ['required', 'string', 'max:20', Rule::unique('KhuyenMai', 'MaCode')->ignore($hienTai, 'MaKM')],
            'TenKM' => ['required', 'string', 'max:100'],
            'PhanTramGiam' => ['required', 'integer', 'between:1,100'],
            'TienGiamToiDa' => ['required', 'numeric', 'min:0'],
            'DonToiThieu' => ['required', 'numeric', 'min:0'],
            'SoLuong' => ['required', 'integer', 'min:0'],
            'NgayBatDau' => ['required', 'date'],
            'NgayKetThuc' => ['required', 'date', 'after_or_equal:NgayBatDau'],
            'LoaiKM' => ['required', 'in:SAN_CO,NHAP_MA'],
            'TrangThai' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'MaCode.required' => 'Vui lòng nhập mã khuyến mãi.',
            'MaCode.unique' => 'Mã khuyến mãi đã tồn tại.',
            'TenKM.required' => 'Vui lòng nhập tên chương trình.',
            'PhanTramGiam.between' => 'Phần trăm giảm phải từ 1 đến 100.',
            'NgayKetThuc.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ];
    }
}
