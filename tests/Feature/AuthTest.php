<?php

namespace Tests\Feature;

use App\Models\KhachHang;
use App\Models\TaiKhoan;
use App\Models\TokenQuenMatKhau;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function taoTaiKhoan(string $ten, string $quyen = TaiKhoan::ROLE_CUSTOMER, bool $hoatDong = true): TaiKhoan
    {
        return TaiKhoan::create([
            'TenDangNhap' => $ten, 'MatKhau' => '123456', 'Quyen' => $quyen,
            'TrangThai' => $hoatDong, 'LoaiTaiKhoan' => 'LOCAL',
        ]);
    }

    public function test_dang_nhap_dung_mat_khau(): void
    {
        $this->taoTaiKhoan('khach1');

        $this->post('/login', ['username' => 'khach1', 'password' => '123456'])->assertRedirect(route('home'));
        $this->assertAuthenticatedAs(TaiKhoan::find('khach1'));
    }

    public function test_dang_nhap_sai_mat_khau_bi_tu_choi(): void
    {
        $this->taoTaiKhoan('khach1');

        $this->from('/login')->post('/login', ['username' => 'khach1', 'password' => 'sai'])
            ->assertRedirect('/login')->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_tai_khoan_bi_khoa_khong_dang_nhap_duoc(): void
    {
        $this->taoTaiKhoan('khoa1', hoatDong: false);

        $this->post('/login', ['username' => 'khoa1', 'password' => '123456'])->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_admin_dang_nhap_vao_trang_quan_tri(): void
    {
        $this->taoTaiKhoan('admin1', TaiKhoan::ROLE_ADMIN);

        $this->post('/login', ['username' => 'admin1', 'password' => '123456'])->assertRedirect(route('admin.dashboard'));
    }

    public function test_khach_chua_dang_nhap_bi_chuyen_ve_trang_dang_nhap(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    public function test_khach_hang_khong_vao_duoc_trang_quan_tri(): void
    {
        $this->actingAs($this->taoTaiKhoan('khach1'))->get('/admin')->assertForbidden();
    }

    public function test_admin_va_nhan_vien_vao_duoc_trang_quan_tri(): void
    {
        $this->actingAs($this->taoTaiKhoan('admin1', TaiKhoan::ROLE_ADMIN))->get('/admin')->assertOk()->assertSee('Dashboard');
        $this->actingAs($this->taoTaiKhoan('nv1', TaiKhoan::ROLE_STAFF))->get('/admin')->assertOk();
    }

    public function test_dang_xuat(): void
    {
        $this->actingAs($this->taoTaiKhoan('khach1'))->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_dang_ky_tao_tai_khoan_chua_kich_hoat_va_kich_hoat_bang_otp(): void
    {
        $this->post('/register', ['username' => 'moi1', 'password' => '123456', 'phone' => '0911222333', 'email' => ''])
            ->assertSessionHasNoErrors();

        $tk = TaiKhoan::find('moi1');
        $this->assertFalse($tk->TrangThai);
        $this->assertSame('ROLE_CUSTOMER', $tk->Quyen);
        $this->assertNotNull(KhachHang::where('TenDangNhap', 'moi1')->first());

        $token = TokenQuenMatKhau::where('TenDangNhap', 'moi1')->firstOrFail();
        $this->post('/verify-otp', ['token' => $token->ChuoiToken, 'type' => 'register', 'otp' => '000000'])
            ->assertSessionHasErrors('otp');
        $this->assertFalse(TaiKhoan::find('moi1')->TrangThai);

        $this->post('/verify-otp', ['token' => $token->ChuoiToken, 'type' => 'register', 'otp' => $token->OTPCode])
            ->assertRedirect(route('login'));
        $this->assertTrue(TaiKhoan::find('moi1')->TrangThai);
    }

    public function test_dang_ky_bao_loi_khi_du_lieu_khong_hop_le(): void
    {
        $this->taoTaiKhoan('trung');

        $this->post('/register', ['username' => 'trung', 'password' => '123', 'phone' => 'abc'])
            ->assertSessionHasErrors(['username', 'password', 'phone']);
    }

    public function test_quen_mat_khau_bat_buoc_nhap_dung_otp_truoc_khi_doi(): void
    {
        $this->taoTaiKhoan('khach1');
        KhachHang::create(['MaKH' => 'KH001', 'HoTen' => 'A', 'DienThoai' => '0911222333', 'TenDangNhap' => 'khach1']);

        $this->post('/forgot-password', ['phone' => '0911222333'])->assertSessionHasNoErrors();
        $token = TokenQuenMatKhau::where('TenDangNhap', 'khach1')->firstOrFail();

        // Chưa nhập OTP thì không vào được trang đặt lại và không đổi được mật khẩu
        $this->get('/reset-password?token='.$token->ChuoiToken)->assertRedirect(route('password.forgot'));
        $this->post('/reset-password', ['token' => $token->ChuoiToken, 'password' => 'matkhaumoi', 'password_confirmation' => 'matkhaumoi'])
            ->assertRedirect(route('password.forgot'));
        $this->assertTrue(\Hash::check('123456', TaiKhoan::find('khach1')->MatKhau));

        $this->post('/verify-otp', ['token' => $token->ChuoiToken, 'type' => 'forgot', 'otp' => $token->OTPCode])
            ->assertRedirect(route('password.reset', ['token' => $token->ChuoiToken]));
        $this->post('/reset-password', ['token' => $token->ChuoiToken, 'password' => 'matkhaumoi', 'password_confirmation' => 'matkhaumoi'])
            ->assertRedirect(route('login'));
        $this->assertTrue(\Hash::check('matkhaumoi', TaiKhoan::find('khach1')->MatKhau));
    }

    public function test_quen_mat_khau_so_dien_thoai_khong_ton_tai(): void
    {
        $this->post('/forgot-password', ['phone' => '0900000000'])->assertSessionHasErrors('phone');
    }

    public function test_otp_quen_mat_khau_khong_mo_khoa_duoc_tai_khoan_bi_khoa(): void
    {
        $this->taoTaiKhoan('khoa1', hoatDong: false);
        KhachHang::create(['MaKH' => 'KH001', 'HoTen' => 'A', 'DienThoai' => '0911222333', 'TenDangNhap' => 'khoa1']);

        $this->post('/forgot-password', ['phone' => '0911222333']);
        $token = TokenQuenMatKhau::where('TenDangNhap', 'khoa1')->firstOrFail();
        $this->assertSame('forgot', $token->MucDich);

        // Gửi kèm type=register để thử kích hoạt lại tài khoản: mục đích lấy từ CSDL nên bị bỏ qua
        $this->post('/verify-otp', ['token' => $token->ChuoiToken, 'type' => 'register', 'otp' => $token->OTPCode])
            ->assertRedirect(route('password.reset', ['token' => $token->ChuoiToken]));
        $this->assertFalse(TaiKhoan::find('khoa1')->TrangThai);
    }

    public function test_nhap_sai_otp_5_lan_thi_token_bi_xoa(): void
    {
        $this->post('/register', ['username' => 'moi1', 'password' => '123456', 'phone' => '0911222333']);
        $token = TokenQuenMatKhau::where('TenDangNhap', 'moi1')->firstOrFail();
        $dung = $token->OTPCode;
        $sai = $dung === '000000' ? '111111' : '000000';

        for ($i = 0; $i < 5; $i++) {
            $this->post('/verify-otp', ['token' => $token->ChuoiToken, 'otp' => $sai])->assertSessionHasErrors('otp');
        }

        $this->assertNull(TokenQuenMatKhau::where('ChuoiToken', $token->ChuoiToken)->first());
        $this->post('/verify-otp', ['token' => $token->ChuoiToken, 'otp' => $dung])->assertSessionHasErrors('otp');
        $this->assertFalse(TaiKhoan::find('moi1')->TrangThai);
    }

    public function test_xin_otp_qua_3_lan_cho_mot_so_dien_thoai_bi_chan(): void
    {
        $this->taoTaiKhoan('khach1');
        KhachHang::create(['MaKH' => 'KH001', 'HoTen' => 'A', 'DienThoai' => '0911222333', 'TenDangNhap' => 'khach1']);

        for ($i = 0; $i < 3; $i++) {
            $this->post('/forgot-password', ['phone' => '0911222333'])->assertSessionHasNoErrors();
        }
        $this->post('/forgot-password', ['phone' => '0911222333'])->assertSessionHasErrors('phone');
        $this->assertSame(3, TokenQuenMatKhau::where('TenDangNhap', 'khach1')->count());
    }
}
