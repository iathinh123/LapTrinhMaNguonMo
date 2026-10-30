<?php

use App\Http\Controllers\Admin\DanhMucController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonHangController as AdminDonHangController;
use App\Http\Controllers\Admin\KhuyenMaiController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\NhaCungCapController;
use App\Http\Controllers\Admin\NhapKhoController;
use App\Http\Controllers\Admin\SanPhamController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BinhLuanController;
use App\Http\Controllers\DonHangController;
use App\Http\Controllers\GioHangController;
use App\Http\Controllers\HoSoController;
use App\Http\Controllers\HomeController;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Route;

/* ===== Công khai ===== */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{sanPham}', [HomeController::class, 'show'])->name('product.show');

/* ===== Xác thực ===== */
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:10,1');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:5,1');

    Route::get('/forgot-password', [PasswordResetController::class, 'forgotForm'])->name('password.forgot');
    Route::post('/forgot-password', [PasswordResetController::class, 'forgot'])->middleware('throttle:5,1');
    Route::get('/verify-otp', [PasswordResetController::class, 'otpForm'])->name('otp.form');
    Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp'])->middleware('throttle:10,1')->name('otp.verify');
    Route::get('/reset-password', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

/* ===== Khách hàng (cần đăng nhập) ===== */
Route::middleware('auth')->group(function () {
    Route::get('/cart', [GioHangController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [GioHangController::class, 'store'])->name('cart.add');
    Route::post('/cart/update', [GioHangController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{maSp}', [GioHangController::class, 'destroy'])->name('cart.remove');
    Route::get('/cart/checkout', [DonHangController::class, 'checkoutForm'])->name('checkout.form');
    Route::post('/cart/checkout', [DonHangController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [DonHangController::class, 'index'])->name('orders.index');
    Route::post('/orders/cancel/{maHd}', [DonHangController::class, 'huy'])->name('orders.cancel');

    Route::get('/profile', [HoSoController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [HoSoController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [HoSoController::class, 'doiMatKhau'])->name('profile.password');

    Route::post('/product/review', [BinhLuanController::class, 'store'])->name('review.store');
    Route::post('/review/delete/{binhLuan}', [BinhLuanController::class, 'destroy'])->name('review.destroy');
});

/* ===== Quản trị (ADMIN / STAFF) ===== */
Route::middleware(['auth', 'role:'.implode(',', TaiKhoan::ADMIN_ROLES)])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', SanPhamController::class)->except('show');
    Route::resource('danhmuc', DanhMucController::class)->except('show');
    Route::resource('ncc', NhaCungCapController::class)->except('show');
    Route::resource('khuyenmai', KhuyenMaiController::class)->except('show');

    Route::get('donhang', [AdminDonHangController::class, 'index'])->name('donhang.index');
    Route::get('donhang/{donhang}', [AdminDonHangController::class, 'show'])->name('donhang.show');
    Route::post('donhang/{donhang}/trang-thai', [AdminDonHangController::class, 'capNhatTrangThai'])->name('donhang.status');

    Route::resource('nhapkho', NhapKhoController::class)->only(['index', 'create', 'store', 'show']);

    Route::get('users', [NguoiDungController::class, 'index'])->name('users.index');
    Route::post('users/{taiKhoan}/role', [NguoiDungController::class, 'doiQuyen'])->middleware('role:ROLE_ADMIN')->name('users.role');
    Route::post('users/{taiKhoan}/lock', [NguoiDungController::class, 'khoa'])->middleware('role:ROLE_ADMIN')->name('users.lock');
});
