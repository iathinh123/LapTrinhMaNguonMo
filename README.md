# PhuKienRe – Website quản lý bán phụ kiện điện tử (Laravel)

Đồ án cuối môn **Lập trình mã nguồn mở** (Laravel Framework), đề tài *Website bán hàng online*.
Đây là bản làm lại bằng **Laravel 13** từ bản cũ viết bằng Spring Boot (Java) của Nhóm 12.

## 1. Chức năng

**Khách vãng lai:** xem, tìm kiếm, lọc theo danh mục, sắp xếp theo giá, phân trang; xem chi tiết và đánh giá; đăng ký (xác thực OTP); đăng nhập; quên mật khẩu bằng OTP.

**Khách hàng** (kế thừa khách vãng lai): giỏ hàng; đặt hàng (thanh toán khi nhận hàng, áp mã giảm giá, kiểm tra tồn kho); xem và hủy đơn (hoàn kho); bình luận, đánh giá sao; cập nhật hồ sơ; đổi mật khẩu.

**Quản trị viên / Nhân viên** (`/admin`): dashboard (số liệu, đơn mới, sản phẩm sắp hết); CRUD sản phẩm (upload ảnh), danh mục, nhà cung cấp, khuyến mãi; xử lý đơn hàng (đổi trạng thái, hủy hoàn kho); nhập kho (tự cộng tồn); quản lý người dùng (đổi quyền, khóa/mở khóa).

Sơ đồ: [ERD](docs/erd.png) · [Use Case](docs/usecase.png) (mã nguồn sơ đồ: `docs/erd.mmd`, `docs/usecase.svg`).

## 2. Công nghệ

| Thành phần | Công nghệ |
|---|---|
| Backend | Laravel 13, PHP ≥ 8.3 |
| CSDL | MySQL, truy cập bằng Eloquent ORM; cấu trúc bảng bằng Migration; dữ liệu mẫu bằng Seeder + Factory |
| Giao diện | Blade + Bootstrap 5 (CDN), tách layout khách hàng và quản trị |
| Xác thực, phân quyền | Laravel Auth (session guard) + Middleware `role` tự viết |
| Kiểm tra dữ liệu | Form Request / `validate()` kèm thông báo lỗi tiếng Việt |
| Upload file | Laravel Storage (disk `public`) |
| Kiểm thử | PHPUnit (`php artisan test`) |

## 3. Cài đặt

Yêu cầu: **PHP ≥ 8.3** (bật `pdo_mysql`, `mbstring`, `fileinfo`, `gd`, `zip`), **Composer**, **MySQL** (Laragon dùng được ngay).

```bash
# 1. Cài thư viện
composer install

# 2. Tạo file cấu hình rồi sinh khóa ứng dụng
cp .env.example .env
php artisan key:generate

# 3. Tạo database rỗng (tên khớp DB_DATABASE trong .env)
mysql -u root -e "CREATE DATABASE ql_phukien_dientu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"

# 4. Tạo bảng và nạp dữ liệu mẫu
php artisan migrate --seed

# 5. Liên kết thư mục ảnh upload, chạy server
php artisan storage:link
php artisan serve
```

Mở <http://localhost:8000>. Nếu MySQL có mật khẩu hoặc khác cổng, sửa `DB_USERNAME`, `DB_PASSWORD`, `DB_PORT` trong `.env`.

> Trên Laragon (Windows): mở Terminal của Laragon trong thư mục dự án và chạy các lệnh trên. Nếu `composer install` báo thiếu `ext-zip`, bật dòng `extension=zip` trong `php.ini`.

**Cách khác – nhập thẳng file SQL** (không cần chạy migration): file [`database/ql_phukien_dientu.sql`](database/ql_phukien_dientu.sql) tạo database `ql_phukien_dientu`, 17 bảng và dữ liệu mẫu (3 tài khoản, 7 danh mục, 5 nhà cung cấp, 20 sản phẩm, 2 voucher). Chạy bước 1–2 ở trên, rồi thay bước 3–4 bằng:

```bash
mysql -u root < database/ql_phukien_dientu.sql
```

Hoặc nhập file này bằng HeidiSQL / phpMyAdmin. Sau đó chạy tiếp `php artisan storage:link` và `php artisan serve`. Không dùng song song với `migrate`, vì bảng đã tồn tại.

Muốn đặt lại dữ liệu: `php artisan migrate:fresh --seed`.
Nếu không muốn 30 sản phẩm, 10 khách và 15 hóa đơn ngẫu nhiên (dùng thử phân trang), xóa 3 dòng `::factory()` trong `database/seeders/DatabaseSeeder.php`.

## 4. Tài khoản mẫu (mật khẩu: `123456`)

| Tài khoản | Quyền | Vào được |
|---|---|---|
| `admin` | ROLE_ADMIN | `/admin` |
| `nhanvien` | ROLE_STAFF | `/admin` |
| `khachhang` | ROLE_CUSTOMER | khu vực khách hàng |

Mã giảm giá thử: `GIAM10` (giảm 10%, tối đa 50.000đ, đơn từ 200.000đ), `FREESHIP20` (giảm 20%, tối đa 100.000đ, đơn từ 500.000đ).

**OTP:** mặc định chế độ demo (`SMS_PROVIDER=mock`): mã OTP hiện ngay trên màn hình sau khi đăng ký hoặc quên mật khẩu, đồng thời ghi vào `storage/logs/sms.log`. Gửi SMS thật: đặt `SMS_PROVIDER=speedsms` và `SPEEDSMS_TOKEN` trong `.env`. Email OTP (chỉ khi khách có email) mặc định ghi vào `storage/logs/laravel.log`.

## 5. Cấu trúc thư mục chính

```
app/Http/Controllers/        controller khách hàng; Admin/ và Auth/ cho quản trị và xác thực
app/Http/Middleware/EnsureRole.php   middleware phân quyền theo vai trò
app/Http/Requests/           Form Request (Auth/, Admin/, CheckoutRequest)
app/Models/                  Eloquent model + quan hệ (Vietnamese table/column, khóa chính chuỗi)
app/Services/                nghiệp vụ: AuthService, GioHangService, DonHangService, SmsService
app/Support/IdGen.php        sinh mã tự tăng (SP001, HD00A, KM001...)
database/migrations/         17 migration (khóa ngoại, khóa kép, soft delete)
database/seeders, factories/ dữ liệu mẫu
resources/views/             layouts, includes, customers/, admin/, emails/
routes/web.php               toàn bộ đường dẫn
tests/Feature/               39 test (xác thực, mua hàng, quản trị)
docs/                        ERD, Use Case
```

## 6. Đối chiếu yêu cầu đồ án

| Yêu cầu | Đáp ứng |
|---|---|
| Laravel, MySQL, Eloquent, Migration, Seeder/Factory | `app/Models`, `database/migrations`, `database/seeders`, `database/factories` |
| Blade + Bootstrap, tách khu vực user/admin | `resources/views/layouts/customer.blade.php`, `admin.blade.php` |
| ≥ 2 role, Laravel Auth + Middleware | 3 role; middleware `auth` và `role:ROLE_ADMIN,ROLE_STAFF` bọc nhóm `/admin` |
| CRUD ≥ 2 bảng có quan hệ | SanPham ↔ DanhMuc, NhaCungCap; KhuyenMai; HoaDon ↔ ChiTietHoaDon |
| Tìm kiếm + phân trang giữ từ khóa | `paginate()->withQueryString()` ở trang chủ và các danh sách admin |
| Validation + thông báo lỗi | Form Request và `validate()`, hiển thị bằng `@error` |
| Flash message | `session('success' / 'error' / 'warning')` trong `includes/flash.blade.php` |
| Mở rộng: upload file | Ảnh sản phẩm qua `Storage::disk('public')` |
| Mở rộng: Soft Delete | SanPham, DanhMuc, NhaCungCap, KhuyenMai |
| Mở rộng: Middleware tùy chỉnh | `EnsureRole` |
| Mở rộng: responsive | Bootstrap 5 grid |

## 7. Chạy kiểm thử

```bash
mysql -u root -e "CREATE DATABASE ql_phukien_dientu_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
php artisan test
```

Test dùng database riêng `ql_phukien_dientu_test` (cấu hình trong `phpunit.xml`) nên không ảnh hưởng dữ liệu đang dùng.

## 8. Hạn chế

Bản Laravel này làm gọn hơn bản Java: chưa có thanh toán VNPay (chỉ thanh toán khi nhận hàng), đăng nhập Google, chat hỗ trợ, email xác nhận đơn, thống kê doanh thu theo tháng, xuất Excel, in hóa đơn. Các bảng `TinNhan`, `ThongBao`, `KhachHang_KhuyenMai` vẫn có trong CSDL nhưng chưa dùng trong giao diện.
