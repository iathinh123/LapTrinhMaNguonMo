<?php

namespace Tests\Feature;

use App\Models\BinhLuan;
use App\Models\ChiTietGioHang;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\KhuyenMai;
use App\Models\SanPham;
use App\Models\TaiKhoan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MuaHangTest extends TestCase
{
    use RefreshDatabase;

    private function khach(string $ten = 'khach1', string $ma = 'KH001'): TaiKhoan
    {
        $tk = TaiKhoan::create(['TenDangNhap' => $ten, 'MatKhau' => '123456', 'Quyen' => 'ROLE_CUSTOMER', 'TrangThai' => true, 'LoaiTaiKhoan' => 'LOCAL']);
        KhachHang::create(['MaKH' => $ma, 'HoTen' => 'Khách '.$ma, 'DienThoai' => '09000000'.substr($ma, -2), 'TenDangNhap' => $ten]);

        return $tk;
    }

    private function sp(string $ma, int $sl = 10, int $gia = 100000, string $ten = 'San pham'): SanPham
    {
        return SanPham::create(['MaSP' => $ma, 'TenSP' => $ten.' '.$ma, 'Gia' => $gia, 'SoLuong' => $sl, 'NguongToiThieu' => 2, 'TinhTrang' => 'Còn hàng', 'CreatedDate' => now()]);
    }

    public function test_trang_chu_tim_kiem_va_phan_trang_giu_tu_khoa(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            $this->sp(sprintf('SP%03d', $i), ten: 'Chuot');
        }
        $this->sp('SP100', ten: 'Banphim');

        $res = $this->get('/?keyword=Chuot');
        $res->assertOk()->assertDontSee('Banphim')->assertSee('keyword=Chuot', false);
        $this->assertCount(12, $res->viewData('sanPhams'));
        $this->assertSame(15, $res->viewData('sanPhams')->total());

        $this->get('/?keyword=Chuot&page=2')->assertOk();
        $this->assertCount(3, $this->get('/?keyword=Chuot&page=2')->viewData('sanPhams'));
    }

    public function test_sap_xep_theo_gia(): void
    {
        $this->sp('SP001', gia: 300000);
        $this->sp('SP002', gia: 100000);

        $this->assertSame('SP002', $this->get('/?sort=priceAsc')->viewData('sanPhams')->first()->MaSP);
        $this->assertSame('SP001', $this->get('/?sort=priceDesc')->viewData('sanPhams')->first()->MaSP);
    }

    public function test_chua_dang_nhap_khong_vao_gio_hang(): void
    {
        $this->get('/cart')->assertRedirect(route('login'));
    }

    public function test_them_vao_gio_va_chan_vuot_ton_kho(): void
    {
        $this->sp('SP001', sl: 3);
        $u = $this->khach();

        $this->actingAs($u)->post('/cart/add', ['maSp' => 'SP001', 'soLuong' => 2])->assertRedirect(route('cart.index'));
        $this->assertSame(2, (int) ChiTietGioHang::where('MaKH', 'KH001')->value('SoLuong'));

        $this->actingAs($u)->post('/cart/add', ['maSp' => 'SP001', 'soLuong' => 2])->assertSessionHasErrors('gioHang');
        $this->assertSame(2, (int) ChiTietGioHang::where('MaKH', 'KH001')->value('SoLuong'));

        $this->actingAs($u)->post('/cart/update', ['maSp' => 'SP001', 'soLuong' => 3])->assertSessionHasNoErrors();
        $this->actingAs($u)->post('/cart/remove/SP001')->assertRedirect(route('cart.index'));
        $this->assertSame(0, ChiTietGioHang::where('MaKH', 'KH001')->count());
    }

    public function test_dat_hang_tu_gio_tru_kho_ap_voucher_va_xoa_gio(): void
    {
        $this->sp('SP001', sl: 5, gia: 150000);
        $this->sp('SP002', sl: 5, gia: 100000);
        KhuyenMai::create(['MaKM' => 'KM001', 'MaCode' => 'GIAM10', 'PhanTramGiam' => 10, 'TienGiamToiDa' => 50000, 'DonToiThieu' => 200000,
            'SoLuong' => 5, 'NgayBatDau' => now()->subDay(), 'NgayKetThuc' => now()->addDay(), 'TrangThai' => true, 'LoaiKM' => 'NHAP_MA']);
        $u = $this->khach();
        ChiTietGioHang::create(['MaKH' => 'KH001', 'MaSP' => 'SP001', 'SoLuong' => 2]);
        ChiTietGioHang::create(['MaKH' => 'KH001', 'MaSP' => 'SP002', 'SoLuong' => 1]);

        $this->actingAs($u)->post('/cart/checkout', ['diaChi' => '1 Le Loi', 'sdt' => '0911222333', 'maKm' => 'GIAM10'])
            ->assertRedirect(route('orders.index'))->assertSessionHasNoErrors();

        $hd = HoaDon::firstOrFail();
        $this->assertSame(400000.0, (float) $hd->TienGiamGia + (float) $hd->TongTien);  // 300k + 100k
        $this->assertEquals(40000, $hd->TienGiamGia);                                      // 10% của 400k = 40k < trần 50k
        $this->assertEquals(360000, $hd->TongTien);
        $this->assertSame(3, SanPham::find('SP001')->SoLuong);
        $this->assertSame(4, SanPham::find('SP002')->SoLuong);
        $this->assertSame(4, KhuyenMai::find('KM001')->SoLuong);
        $this->assertSame(2, $hd->chiTiet()->count());
        $this->assertSame(0, ChiTietGioHang::where('MaKH', 'KH001')->count());
    }

    public function test_dat_hang_khong_du_ton_kho_bi_tu_choi_va_khong_tru_kho(): void
    {
        $this->sp('SP001', sl: 1);
        $u = $this->khach();

        $this->actingAs($u)->post('/cart/checkout', ['maSp' => 'SP001', 'soLuong' => 2, 'diaChi' => 'x', 'sdt' => '0911222333'])
            ->assertSessionHasErrors('donHang');
        $this->assertSame(0, HoaDon::count());
        $this->assertSame(1, SanPham::find('SP001')->SoLuong);
    }

    public function test_voucher_sai_thi_khong_tao_don_va_khong_tru_kho(): void
    {
        $this->sp('SP001', sl: 5);
        $u = $this->khach();

        $this->actingAs($u)->post('/cart/checkout', ['maSp' => 'SP001', 'soLuong' => 1, 'diaChi' => 'x', 'sdt' => '0911222333', 'maKm' => 'KHONGCO'])
            ->assertSessionHasErrors('maKm');
        $this->assertSame(0, HoaDon::count());
        $this->assertSame(5, SanPham::find('SP001')->SoLuong);
    }

    public function test_thanh_toan_sdt_sai_bao_loi_validation(): void
    {
        $this->sp('SP001');
        $this->actingAs($this->khach())->post('/cart/checkout', ['maSp' => 'SP001', 'diaChi' => '', 'sdt' => 'abc'])
            ->assertSessionHasErrors(['diaChi', 'sdt']);
    }

    public function test_huy_don_hoan_kho_va_khong_huy_hai_lan(): void
    {
        $this->sp('SP001', sl: 5);
        $u = $this->khach();
        $this->actingAs($u)->post('/cart/checkout', ['maSp' => 'SP001', 'soLuong' => 2, 'diaChi' => 'x', 'sdt' => '0911222333']);
        $this->assertSame(3, SanPham::find('SP001')->SoLuong);
        $ma = HoaDon::firstOrFail()->MaHD;

        $this->actingAs($u)->post("/orders/cancel/$ma")->assertRedirect(route('orders.index'));
        $this->assertSame(5, SanPham::find('SP001')->SoLuong);
        $this->assertSame('Đã hủy', HoaDon::find($ma)->TrangThai);

        $this->actingAs($u)->post("/orders/cancel/$ma")->assertSessionHasErrors('donHang');
        $this->assertSame(5, SanPham::find('SP001')->SoLuong); // không cộng kho lần hai
    }

    public function test_khong_huy_duoc_don_cua_nguoi_khac(): void
    {
        $this->sp('SP001', sl: 5);
        $a = $this->khach('khach1', 'KH001');
        $b = $this->khach('khach2', 'KH002');
        $this->actingAs($a)->post('/cart/checkout', ['maSp' => 'SP001', 'soLuong' => 1, 'diaChi' => 'x', 'sdt' => '0911222333']);

        $this->actingAs($b)->post('/orders/cancel/'.HoaDon::firstOrFail()->MaHD)->assertForbidden();
    }

    public function test_binh_luan_va_xoa_binh_luan(): void
    {
        $this->sp('SP001');
        $u = $this->khach();

        $this->actingAs($u)->post('/product/review', ['maSp' => 'SP001', 'soSao' => 4, 'noiDung' => 'Tốt'])->assertSessionHasNoErrors();
        $bl = BinhLuan::firstOrFail();
        $this->get('/product/SP001')->assertOk()->assertSee('Tốt');

        $this->actingAs($this->khach('khach2', 'KH002'))->post('/review/delete/'.$bl->MaBL)->assertForbidden();
        $this->actingAs($u)->post('/review/delete/'.$bl->MaBL)->assertRedirect();
        $this->assertSame(0, BinhLuan::count());
    }

    public function test_cap_nhat_ho_so_va_doi_mat_khau(): void
    {
        $u = $this->khach();

        $this->actingAs($u)->post('/profile/update', ['hoTen' => 'Tên Mới', 'dienThoai' => '0911222333', 'email' => 'a@b.com', 'diaChi' => 'HCM'])
            ->assertSessionHasNoErrors();
        $this->assertSame('Tên Mới', KhachHang::find('KH001')->HoTen);

        $this->actingAs($u)->post('/profile/change-password', ['oldPassword' => 'sai', 'newPassword' => 'matkhau2', 'newPassword_confirmation' => 'matkhau2'])
            ->assertSessionHasErrors('oldPassword');
        $this->actingAs($u)->post('/profile/change-password', ['oldPassword' => '123456', 'newPassword' => 'matkhau2', 'newPassword_confirmation' => 'matkhau2'])
            ->assertSessionHasNoErrors();
        $this->assertTrue(\Hash::check('matkhau2', TaiKhoan::find('khach1')->MatKhau));
    }

    public function test_trang_chi_tiet_san_pham_da_xoa_mem_tra_ve_404(): void
    {
        $sp = $this->sp('SP001');
        $sp->delete();

        $this->get('/product/SP001')->assertNotFound();
    }
}
