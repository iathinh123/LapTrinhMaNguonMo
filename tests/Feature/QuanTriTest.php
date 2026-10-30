<?php

namespace Tests\Feature;

use App\Models\DanhMuc;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\KhuyenMai;
use App\Models\NhaCungCap;
use App\Models\PhieuNhap;
use App\Models\SanPham;
use App\Models\TaiKhoan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QuanTriTest extends TestCase
{
    use RefreshDatabase;

    private function admin(string $quyen = 'ROLE_ADMIN'): TaiKhoan
    {
        return TaiKhoan::create(['TenDangNhap' => 'ad_'.strtolower($quyen), 'MatKhau' => '123456', 'Quyen' => $quyen, 'TrangThai' => true, 'LoaiTaiKhoan' => 'LOCAL']);
    }

    private function nenTang(): array
    {
        return [DanhMuc::create(['MaDM' => 'DM01', 'TenDM' => 'Ốp lưng']), NhaCungCap::create(['MaNCC' => 'NCC01', 'TenNCC' => 'An Phát'])];
    }

    private function sp(string $ma = 'SP001', int $sl = 10): SanPham
    {
        return SanPham::create(['MaSP' => $ma, 'TenSP' => 'Ốp '.$ma, 'MaDM' => 'DM01', 'MaNCC' => 'NCC01', 'Gia' => 100000, 'SoLuong' => $sl, 'NguongToiThieu' => 2, 'TinhTrang' => 'Còn hàng', 'CreatedDate' => now()]);
    }

    private function duLieuSp(array $ghiDe = []): array
    {
        return $ghiDe + ['TenSP' => 'Ốp mới', 'MaDM' => 'DM01', 'MaNCC' => 'NCC01', 'Gia' => 150000, 'SoLuong' => 5, 'NguongToiThieu' => 2];
    }

    public function test_khach_hang_khong_vao_duoc_cac_trang_quan_tri(): void
    {
        $u = TaiKhoan::create(['TenDangNhap' => 'k', 'MatKhau' => '123456', 'Quyen' => 'ROLE_CUSTOMER', 'TrangThai' => true, 'LoaiTaiKhoan' => 'LOCAL']);

        foreach (['/admin', '/admin/products', '/admin/danhmuc', '/admin/ncc', '/admin/khuyenmai', '/admin/donhang', '/admin/nhapkho', '/admin/users'] as $url) {
            $this->actingAs($u)->get($url)->assertForbidden();
        }
    }

    public function test_nhan_vien_vao_duoc_dashboard_va_cac_trang_danh_sach(): void
    {
        $this->nenTang();
        $this->sp(sl: 1); // sắp hết hàng -> hiện ở dashboard
        $nv = $this->admin('ROLE_STAFF');

        $this->actingAs($nv)->get('/admin')->assertOk()->assertSee('Ốp SP001');
        foreach (['/admin/products', '/admin/danhmuc', '/admin/ncc', '/admin/khuyenmai', '/admin/donhang', '/admin/nhapkho', '/admin/users'] as $url) {
            $this->actingAs($nv)->get($url)->assertOk();
        }
    }

    public function test_them_sua_xoa_mem_san_pham_co_upload_anh(): void
    {
        Storage::fake('public');
        $this->nenTang();
        $ad = $this->admin();

        $this->actingAs($ad)->post('/admin/products', $this->duLieuSp(['HinhAnh' => UploadedFile::fake()->image('a.jpg')]))
            ->assertRedirect(route('admin.products.index'))->assertSessionHasNoErrors();
        $sp = SanPham::firstOrFail();
        $this->assertSame('SP001', $sp->MaSP);
        $this->assertSame('Còn hàng', $sp->TinhTrang);
        Storage::disk('public')->assertExists('products/'.$sp->HinhAnh);

        $this->actingAs($ad)->put('/admin/products/SP001', $this->duLieuSp(['SoLuong' => 0]))->assertSessionHasNoErrors();
        $this->assertSame('Hết hàng', $sp->fresh()->TinhTrang);

        $this->actingAs($ad)->delete('/admin/products/SP001')->assertRedirect();
        $this->assertSame(0, SanPham::count());
        $this->assertSame(1, SanPham::withTrashed()->count());
    }

    public function test_san_pham_bao_loi_validation(): void
    {
        $this->nenTang();

        $this->actingAs($this->admin())->post('/admin/products', ['TenSP' => '', 'Gia' => -5, 'SoLuong' => 'x', 'HinhAnh' => UploadedFile::fake()->create('a.pdf', 10)])
            ->assertSessionHasErrors(['TenSP', 'MaDM', 'MaNCC', 'Gia', 'SoLuong', 'HinhAnh']);
    }

    public function test_tim_kiem_va_phan_trang_san_pham_admin_giu_tu_khoa(): void
    {
        $this->nenTang();
        for ($i = 1; $i <= 12; $i++) {
            SanPham::create(['MaSP' => sprintf('SP%03d', $i), 'TenSP' => 'Chuot '.$i, 'MaDM' => 'DM01', 'MaNCC' => 'NCC01', 'Gia' => 1, 'SoLuong' => 1, 'CreatedDate' => now()]);
        }
        $this->sp('SP100');

        $res = $this->actingAs($this->admin())->get('/admin/products?keyword=Chuot');
        $this->assertSame(12, $res->viewData('sanPhams')->total());
        $res->assertSee('keyword=Chuot', false);
    }

    public function test_danh_muc_crud_va_chan_xoa_khi_con_san_pham(): void
    {
        $this->nenTang();
        $this->sp();
        $ad = $this->admin();

        $this->actingAs($ad)->post('/admin/danhmuc', ['TenDM' => 'Tai nghe'])->assertSessionHasNoErrors();
        $this->assertSame('DM02', DanhMuc::where('TenDM', 'Tai nghe')->value('MaDM'));
        $this->actingAs($ad)->post('/admin/danhmuc', ['TenDM' => ''])->assertSessionHasErrors('TenDM');

        $this->actingAs($ad)->delete('/admin/danhmuc/DM01')->assertSessionHas('error');
        $this->assertNotNull(DanhMuc::find('DM01'));
        $this->actingAs($ad)->delete('/admin/danhmuc/DM02')->assertSessionHas('success');
        $this->assertNull(DanhMuc::find('DM02'));
    }

    public function test_nha_cung_cap_crud_va_validation(): void
    {
        $ad = $this->admin();

        $this->actingAs($ad)->post('/admin/ncc', ['TenNCC' => 'ABC', 'SoDienThoai' => '0912345678', 'Email' => 'a@b.com'])->assertSessionHasNoErrors();
        $this->assertSame('NCC01', NhaCungCap::firstOrFail()->MaNCC);
        $this->actingAs($ad)->post('/admin/ncc', ['TenNCC' => 'X', 'SoDienThoai' => 'abc', 'Email' => 'sai'])->assertSessionHasErrors(['SoDienThoai', 'Email']);
        $this->actingAs($ad)->put('/admin/ncc/NCC01', ['TenNCC' => 'ABC mới'])->assertSessionHasNoErrors();
        $this->assertSame('ABC mới', NhaCungCap::find('NCC01')->TenNCC);
    }

    public function test_khuyen_mai_tao_sua_va_trung_ma(): void
    {
        $ad = $this->admin();
        $d = ['MaCode' => 'SALE5', 'TenKM' => 'Giảm 5%', 'PhanTramGiam' => 5, 'TienGiamToiDa' => 20000, 'DonToiThieu' => 100000, 'SoLuong' => 10,
            'NgayBatDau' => '2026-01-01', 'NgayKetThuc' => '2026-12-31', 'LoaiKM' => 'NHAP_MA', 'TrangThai' => 1];

        $this->actingAs($ad)->post('/admin/khuyenmai', $d)->assertSessionHasNoErrors();
        $this->assertTrue(KhuyenMai::find('KM001')->TrangThai);

        $this->actingAs($ad)->post('/admin/khuyenmai', $d)->assertSessionHasErrors('MaCode');
        $this->actingAs($ad)->put('/admin/khuyenmai/KM001', ['TrangThai' => null] + $d)->assertSessionHasNoErrors(); // tự trùng mã của chính nó thì được
        $this->assertFalse(KhuyenMai::find('KM001')->TrangThai);
        $this->actingAs($ad)->post('/admin/khuyenmai', ['MaCode' => 'B', 'NgayBatDau' => '2026-05-01', 'NgayKetThuc' => '2026-01-01'] + $d)
            ->assertSessionHasErrors('NgayKetThuc');
    }

    public function test_admin_doi_trang_thai_don_va_huy_hoan_kho(): void
    {
        $this->nenTang();
        $this->sp(sl: 8);
        KhachHang::create(['MaKH' => 'KH001', 'HoTen' => 'A']);
        $hd = HoaDon::create(['MaHD' => 'HD001', 'MaKH' => 'KH001', 'TongTien' => 200000, 'TrangThai' => HoaDon::ST_COD, 'NgayLap' => now()]);
        $hd->chiTiet()->create(['MaSP' => 'SP001', 'SoLuong' => 2, 'DonGia' => 100000]);
        $ad = $this->admin();

        $this->actingAs($ad)->post('/admin/donhang/HD001/trang-thai', ['trangThai' => 'Đã giao'])->assertSessionHasNoErrors();
        $this->assertSame('Đã giao', $hd->fresh()->TrangThai);
        $this->assertNotNull($hd->fresh()->NgayThanhToan);

        $this->actingAs($ad)->post('/admin/donhang/HD001/trang-thai', ['trangThai' => 'Trạng thái lạ'])->assertSessionHasErrors('trangThai');

        // đơn đã giao không hủy được
        $this->actingAs($ad)->post('/admin/donhang/HD001/trang-thai', ['trangThai' => 'Đã hủy'])->assertSessionHasErrors('donHang');
        $this->assertSame(8, SanPham::find('SP001')->SoLuong);

        $hd2 = HoaDon::create(['MaHD' => 'HD002', 'MaKH' => 'KH001', 'TongTien' => 100000, 'TrangThai' => HoaDon::ST_COD, 'NgayLap' => now()]);
        $hd2->chiTiet()->create(['MaSP' => 'SP001', 'SoLuong' => 3, 'DonGia' => 100000]);
        $this->actingAs($ad)->post('/admin/donhang/HD002/trang-thai', ['trangThai' => 'Đã hủy'])->assertSessionHasNoErrors();
        $this->assertSame(11, SanPham::find('SP001')->SoLuong);
        $this->actingAs($ad)->post('/admin/donhang/HD002/trang-thai', ['trangThai' => 'Đang giao'])->assertSessionHas('error');
        $this->actingAs($ad)->get('/admin/donhang/HD001')->assertOk()->assertSee('Ốp SP001');
        $this->actingAs($ad)->get('/admin/donhang?trangThai=Đã giao')->assertOk()->assertSee('HD001')->assertDontSee('HD002');
    }

    public function test_nhap_kho_cong_ton_va_tinh_tong(): void
    {
        $this->nenTang();
        $this->sp('SP001', 1);
        $this->sp('SP002', 0)->update(['TinhTrang' => 'Hết hàng']);
        $ad = $this->admin();

        $this->actingAs($ad)->post('/admin/nhapkho', [
            'MaNCC' => 'NCC01', 'GhiChu' => 'Lô 1',
            'dong' => [
                ['MaSP' => 'SP001', 'SoLuong' => 10, 'GiaNhap' => 50000],
                ['MaSP' => 'SP002', 'SoLuong' => 5, 'GiaNhap' => 20000],
                ['MaSP' => '', 'SoLuong' => '', 'GiaNhap' => ''],
            ],
        ])->assertRedirect(route('admin.nhapkho.show', 'PN001'))->assertSessionHasNoErrors();

        $this->assertSame(11, SanPham::find('SP001')->SoLuong);
        $this->assertSame(5, SanPham::find('SP002')->SoLuong);
        $this->assertSame('Còn hàng', SanPham::find('SP002')->TinhTrang); // từ "Hết hàng" sang "Còn hàng" sau khi nhập
        $this->assertEquals(600000, PhieuNhap::find('PN001')->TongTienNhap);
        $this->actingAs($ad)->get('/admin/nhapkho/PN001')->assertOk()->assertSee('Ốp SP001');
    }

    public function test_nhap_kho_thieu_du_lieu_khong_tao_phieu(): void
    {
        $this->nenTang();
        $this->sp();

        $ad = $this->admin();
        $this->actingAs($ad)->post('/admin/nhapkho', ['MaNCC' => 'NCC01', 'dong' => [['MaSP' => 'SP001', 'SoLuong' => '', 'GiaNhap' => '']]])
            ->assertSessionHasErrors('dong');
        $this->actingAs($ad)->post('/admin/nhapkho', ['MaNCC' => 'NCC01', 'dong' => [['MaSP' => '', 'SoLuong' => '', 'GiaNhap' => '']]])
            ->assertSessionHasErrors('dong');
        $this->assertSame(0, PhieuNhap::count());
        $this->assertSame(10, SanPham::find('SP001')->SoLuong);
    }

    public function test_doi_quyen_khoa_tai_khoan_va_khong_tu_khoa_chinh_minh(): void
    {
        $ad = $this->admin();
        $k = TaiKhoan::create(['TenDangNhap' => 'khach', 'MatKhau' => '123456', 'Quyen' => 'ROLE_CUSTOMER', 'TrangThai' => true, 'LoaiTaiKhoan' => 'LOCAL']);

        $this->actingAs($ad)->post('/admin/users/khach/role', ['quyen' => 'ROLE_STAFF'])->assertSessionHasNoErrors();
        $this->assertSame('ROLE_STAFF', $k->fresh()->Quyen);
        $this->actingAs($ad)->post('/admin/users/khach/role', ['quyen' => 'ROLE_ROOT'])->assertSessionHasErrors('quyen');

        $this->actingAs($ad)->post('/admin/users/khach/lock');
        $this->assertFalse($k->fresh()->TrangThai);
        $this->actingAs($ad)->post('/admin/users/khach/lock');
        $this->assertTrue($k->fresh()->TrangThai);

        $this->actingAs($ad)->post('/admin/users/'.$ad->TenDangNhap.'/lock')->assertSessionHas('error');
        $this->actingAs($ad)->post('/admin/users/'.$ad->TenDangNhap.'/role', ['quyen' => 'ROLE_CUSTOMER'])->assertSessionHas('error');
        $this->assertTrue($ad->fresh()->TrangThai);
        $this->assertSame('ROLE_ADMIN', $ad->fresh()->Quyen);
    }
}
