-- Cơ sở dữ liệu website bán phụ kiện điện tử (khớp với các migration của Laravel).
-- Cách dùng: mysql -u root < ql_phukien_dientu.sql   (hoặc Import trong HeidiSQL / phpMyAdmin)
-- Mật khẩu của 3 tài khoản mẫu: 123456

CREATE DATABASE ql_phukien_dientu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ql_phukien_dientu;

CREATE TABLE TaiKhoan (
    TenDangNhap VARCHAR(100) PRIMARY KEY,
    MatKhau VARCHAR(255),
    Quyen VARCHAR(20) NOT NULL,
    TrangThai TINYINT(1) NOT NULL DEFAULT 1,
    LoaiTaiKhoan VARCHAR(50) NOT NULL DEFAULT 'LOCAL',
    ProviderId VARCHAR(255)
);

CREATE TABLE TokenQuenMatKhau (
    MaToken INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    TenDangNhap VARCHAR(100) NOT NULL,
    ChuoiToken VARCHAR(255) NOT NULL UNIQUE,
    OTPCode VARCHAR(6),
    NgayTao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    NgayHetHan DATETIME NOT NULL,
    FOREIGN KEY (TenDangNhap) REFERENCES TaiKhoan(TenDangNhap) ON DELETE CASCADE
);

CREATE TABLE KhachHang (
    MaKH CHAR(5) PRIMARY KEY,
    HoTen VARCHAR(100) NOT NULL,
    GioiTinh VARCHAR(10),
    DiaChi VARCHAR(255),
    DienThoai VARCHAR(15),
    Email VARCHAR(100) UNIQUE,
    TenDangNhap VARCHAR(100),
    FOREIGN KEY (TenDangNhap) REFERENCES TaiKhoan(TenDangNhap)
);

CREATE TABLE NhanVien (
    MaNV CHAR(5) PRIMARY KEY,
    HoTen VARCHAR(100) NOT NULL,
    ChucVu VARCHAR(50),
    DienThoai VARCHAR(15),
    TenDangNhap VARCHAR(100),
    FOREIGN KEY (TenDangNhap) REFERENCES TaiKhoan(TenDangNhap)
);

CREATE TABLE DanhMuc (
    MaDM CHAR(5) PRIMARY KEY,
    TenDM VARCHAR(100) NOT NULL,
    deleted_at TIMESTAMP NULL
);

CREATE TABLE NhaCungCap (
    MaNCC CHAR(5) PRIMARY KEY,
    TenNCC VARCHAR(100) NOT NULL,
    DiaChi VARCHAR(255),
    SoDienThoai VARCHAR(20),
    Email VARCHAR(100),
    deleted_at TIMESTAMP NULL
);

CREATE TABLE SanPham (
    MaSP CHAR(5) PRIMARY KEY,
    TenSP VARCHAR(150) NOT NULL,
    MaDM CHAR(5),
    MaNCC CHAR(5),
    Gia DECIMAL(18,2) NOT NULL,
    SoLuong INT NOT NULL DEFAULT 0,
    MoTa VARCHAR(255),
    HinhAnh VARCHAR(255),
    TinhTrang VARCHAR(30) NOT NULL DEFAULT 'Còn hàng',
    NguongToiThieu INT NOT NULL DEFAULT 10,
    CreatedBy VARCHAR(50),
    CreatedDate DATETIME,
    LastModifiedBy VARCHAR(50),
    LastModifiedDate DATETIME,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (MaDM) REFERENCES DanhMuc(MaDM),
    FOREIGN KEY (MaNCC) REFERENCES NhaCungCap(MaNCC)
);

CREATE TABLE BinhLuan (
    MaBL CHAR(5) PRIMARY KEY,
    MaSP CHAR(5),
    MaKH CHAR(5),
    NoiDung VARCHAR(500),
    SoSao TINYINT UNSIGNED,
    NgayDang DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    TrangThai TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (MaSP) REFERENCES SanPham(MaSP) ON DELETE CASCADE,
    FOREIGN KEY (MaKH) REFERENCES KhachHang(MaKH)
);

CREATE TABLE KhuyenMai (
    MaKM CHAR(10) PRIMARY KEY,
    MaCode VARCHAR(20) UNIQUE,
    TenKM VARCHAR(100),
    PhanTramGiam INT NOT NULL DEFAULT 0,
    TienGiamToiDa DECIMAL(18,2) NOT NULL DEFAULT 0,
    DonToiThieu DECIMAL(18,2) NOT NULL DEFAULT 0,
    SoLuong INT NOT NULL DEFAULT 100,
    NgayBatDau DATETIME,
    NgayKetThuc DATETIME,
    TrangThai TINYINT(1) NOT NULL DEFAULT 1,
    LoaiKM VARCHAR(20) NOT NULL DEFAULT 'SAN_CO',
    deleted_at TIMESTAMP NULL
);

CREATE TABLE HoaDon (
    MaHD CHAR(5) PRIMARY KEY,
    MaKH CHAR(5),
    MaNV CHAR(5),
    MaKM CHAR(10),
    TienGiamGia DECIMAL(18,2) NOT NULL DEFAULT 0,
    NgayLap DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    NgayThanhToan DATETIME,
    TongTien DECIMAL(18,2),
    TrangThai VARCHAR(50) NOT NULL DEFAULT 'Chờ thanh toán',
    PhuongThucThanhToan VARCHAR(50),
    DiaChiGiaoHang VARCHAR(255),
    SoDienThoaiGiaoHang VARCHAR(15),
    CreatedBy VARCHAR(50),
    CreatedDate DATETIME,
    LastModifiedBy VARCHAR(50),
    LastModifiedDate DATETIME,
    FOREIGN KEY (MaKH) REFERENCES KhachHang(MaKH),
    FOREIGN KEY (MaNV) REFERENCES NhanVien(MaNV),
    FOREIGN KEY (MaKM) REFERENCES KhuyenMai(MaKM)
);

CREATE TABLE ChiTietHoaDon (
    MaHD CHAR(5),
    MaSP CHAR(5),
    SoLuong INT,
    DonGia DECIMAL(18,2),
    PRIMARY KEY (MaHD, MaSP),
    FOREIGN KEY (MaHD) REFERENCES HoaDon(MaHD) ON DELETE CASCADE,
    FOREIGN KEY (MaSP) REFERENCES SanPham(MaSP)
);

CREATE TABLE ChiTietGioHang (
    MaKH CHAR(5),
    MaSP CHAR(5),
    SoLuong INT,
    NgayThem DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (MaKH, MaSP),
    FOREIGN KEY (MaKH) REFERENCES KhachHang(MaKH) ON DELETE CASCADE,
    FOREIGN KEY (MaSP) REFERENCES SanPham(MaSP) ON DELETE CASCADE
);

CREATE TABLE KhachHang_KhuyenMai (
    MaKH CHAR(5),
    MaKM CHAR(10),
    TrangThaiSuDung TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (MaKH, MaKM),
    FOREIGN KEY (MaKH) REFERENCES KhachHang(MaKH) ON DELETE CASCADE,
    FOREIGN KEY (MaKM) REFERENCES KhuyenMai(MaKM) ON DELETE CASCADE
);

CREATE TABLE PhieuNhap (
    MaPN CHAR(5) PRIMARY KEY,
    MaNV CHAR(5),
    MaNCC CHAR(5),
    NgayNhap DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    TongTienNhap DECIMAL(18,2) NOT NULL DEFAULT 0,
    GhiChu VARCHAR(255),
    FOREIGN KEY (MaNV) REFERENCES NhanVien(MaNV),
    FOREIGN KEY (MaNCC) REFERENCES NhaCungCap(MaNCC)
);

CREATE TABLE ChiTietPhieuNhap (
    MaPN CHAR(5),
    MaSP CHAR(5),
    SoLuongNhap INT,
    GiaNhap DECIMAL(18,2),
    PRIMARY KEY (MaPN, MaSP),
    FOREIGN KEY (MaPN) REFERENCES PhieuNhap(MaPN) ON DELETE CASCADE,
    FOREIGN KEY (MaSP) REFERENCES SanPham(MaSP)
);

CREATE TABLE TinNhan (
    MaTN INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    NguoiGui VARCHAR(100),
    LoaiNguoiGui VARCHAR(20),
    NguoiNhan VARCHAR(100),
    NoiDung TEXT,
    ThoiGian DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    DaDoc TINYINT(1) NOT NULL DEFAULT 0,
    INDEX (NguoiGui),
    INDEX (NguoiNhan)
);

CREATE TABLE ThongBao (
    MaTB INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    TieuDe VARCHAR(255) NOT NULL,
    NoiDung TEXT,
    NgayTao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    TrangThai TINYINT(1) NOT NULL DEFAULT 0,
    MaKH CHAR(5),
    MaSP VARCHAR(50),
    FOREIGN KEY (MaKH) REFERENCES KhachHang(MaKH) ON DELETE SET NULL
);
-- Dữ liệu mẫu

INSERT INTO TaiKhoan (TenDangNhap, MatKhau, Quyen, TrangThai, LoaiTaiKhoan) VALUES
('admin', '$2y$10$LFnMD5B9oCScnnXQkXHbpeS.KIQIyolOB4/Xoc0xPJNQbPV0Gfawy', 'ROLE_ADMIN', 1, 'LOCAL'),
('khachhang', '$2y$10$LFnMD5B9oCScnnXQkXHbpeS.KIQIyolOB4/Xoc0xPJNQbPV0Gfawy', 'ROLE_CUSTOMER', 1, 'LOCAL'),
('nhanvien', '$2y$10$LFnMD5B9oCScnnXQkXHbpeS.KIQIyolOB4/Xoc0xPJNQbPV0Gfawy', 'ROLE_STAFF', 1, 'LOCAL');

INSERT INTO NhanVien (MaNV, HoTen, ChucVu, DienThoai, TenDangNhap) VALUES
('NV001', 'Quản Trị Viên', 'Admin', '0999888777', 'admin'),
('NV002', 'Nhân Viên Bán Hàng', 'Nhân viên', '0988777666', 'nhanvien');

INSERT INTO KhachHang (MaKH, HoTen, GioiTinh, DiaChi, DienThoai, Email, TenDangNhap) VALUES
('KH001', 'Nguyễn Văn A', 'Nam', 'TP.HCM', '0123456789', 'a@gmail.com', 'khachhang');

INSERT INTO DanhMuc (MaDM, TenDM) VALUES
('DM01', 'Ốp lưng'),
('DM02', 'Tai nghe'),
('DM03', 'Sạc dự phòng'),
('DM04', 'Cáp sạc'),
('DM05', 'Kính cường lực'),
('DM06', 'Chuột'),
('DM07', 'Bàn Phím');

INSERT INTO NhaCungCap (MaNCC, TenNCC, DiaChi, SoDienThoai, Email) VALUES
('NCC01', 'Phụ kiện An Phát', 'Hà Nội', '0987654321', 'anphat@gmail.com'),
('NCC02', 'MobileKing', 'TP.HCM', '0912345678', 'mobileking@gmail.com'),
('NCC03', 'TechZone', 'Đà Nẵng', '0905123456', 'techzone@gmail.com'),
('NCC04', 'Phụ kiện Minh Tâm', 'Cần Thơ', '0977888999', 'minhtam@gmail.com'),
('NCC05', 'GadgetPro', 'Hải Phòng', '0933444555', 'gadgetpro@gmail.com');

INSERT INTO SanPham (MaSP, TenSP, MaDM, MaNCC, Gia, SoLuong, MoTa, HinhAnh, TinhTrang, NguongToiThieu, CreatedBy, CreatedDate) VALUES
('SP001', 'Ốp lưng iPhone 14 trong suốt', 'DM01', 'NCC01', '120000.00', 5, 'Ốp lưng nhựa dẻo chống sốc', 'OpLungiPhone14TrongSuot.jpg', 'Sắp hết', 10, 'SYSTEM', NOW()),
('SP002', 'Ốp lưng Samsung S23 chống va đập', 'DM01', 'NCC01', '250000.00', 30, 'Ốp lưng silicon cao cấp', 'OpLungSamSungS23.jpg', 'Còn hàng', 10, 'SYSTEM', NOW()),
('SP003', 'Ốp lưng MagSafe iPhone 15', 'DM01', 'NCC01', '120000.00', 40, 'Hỗ trợ sạc không dây', 'OpLungMagsafeiPhone15.jpg', 'Còn hàng', 10, 'SYSTEM', NOW()),
('SP004', 'Ốp lưng da Galaxy Z Flip 5', 'DM01', 'NCC01', '300000.00', 25, 'Chất liệu da thật, cao cấp', 'OpLungDaGalaxyZFlip5.jpg', 'Còn hàng', 10, 'SYSTEM', NOW()),
('SP005', 'Tai nghe Bluetooth Baseus', 'DM02', 'NCC01', '350000.00', 60, 'Tai nghe không dây pin 8 giờ', 'TaiNgheBluetoothBaseus.jpg', 'Còn hàng', 5, 'SYSTEM', NOW()),
('SP006', 'Tai nghe True Wireless Xiaomi Buds', 'DM02', 'NCC01', '490000.00', 50, 'Chống ồn chủ động, Bluetooth 5.3', 'TaiNgheTrueWirelessXiaomiBuds.jpg', 'Còn hàng', 5, 'SYSTEM', NOW()),
('SP007', 'Tai nghe Apple AirPods 3', 'DM02', 'NCC01', '4500000.00', 20, 'Âm thanh không gian, cảm biến lực', 'AirPods3.jpg', 'Còn hàng', 5, 'SYSTEM', NOW()),
('SP008', 'Tai nghe Samsung Buds2 Pro', 'DM02', 'NCC01', '4200000.00', 25, 'Hỗ trợ âm thanh 24-bit Hi-Fi', 'SamSungBuds2Pro.jpg', 'Còn hàng', 5, 'SYSTEM', NOW()),
('SP009', 'Sạc dự phòng Romoss 20000mAh Sense8', 'DM03', 'NCC01', '590000.00', 40, 'Hỗ trợ sạc nhanh PD 18W, 3 cổng ra', 'SacDuPhongRomoss20000mAhSense8.jpg', 'Còn hàng', 5, 'SYSTEM', NOW()),
('SP010', 'Sạc dự phòng Innostyle PowerGo 10000mAh', 'DM03', 'NCC01', '690000.00', 35, 'Thiết kế nhỏ gọn, sạc nhanh QC 3.0', 'InnostylePowerGo10000mAh.jpg', 'Còn hàng', 5, 'SYSTEM', NOW()),
('SP011', 'Cáp sạc Baseus Type-C 2m', 'DM04', 'NCC01', '259000.00', 80, 'Dây bọc dù siêu bền, hỗ trợ PD 60W', 'CapSacBaseusType-C2m.jpg', 'Còn hàng', 15, 'SYSTEM', NOW()),
('SP012', 'Cáp sạc Remax Lightning RC-094i', 'DM04', 'NCC01', '119000.00', 70, 'Chứng nhận MFi, độ bền cao', 'CapSacRemaxLightningRC-094i.jpg', 'Còn hàng', 15, 'SYSTEM', NOW()),
('SP013', 'Kính cường lực iPhone 14 Pro Max Nillkin', 'DM05', 'NCC01', '129000.00', 50, 'Kính Nillkin 9H, chống vân tay, độ trong cao', 'CuongLuciPhone14ProMaxNillkin.jpg', 'Còn hàng', 5, 'SYSTEM', NOW()),
('SP014', 'Kính cường lực KingKong', 'DM05', 'NCC01', '309000.00', 60, 'Chống trầy, phủ nano, cảm ứng nhạy', 'CuongLucKingKong.jpg', 'Còn hàng', 5, 'SYSTEM', NOW()),
('SP015', 'Chuột không dây Ugreen Silent MU006', 'DM06', 'NCC01', '180000.00', 30, 'Kết nối không dây 2.4 GHz với khoảng cách hoạt động lên tới khoảng 15m, Thiết kế công thái học', 'Chuot-khong-day-Ugreen-Silent-MU006.png', 'Còn hàng', 5, 'SYSTEM', NOW()),
('SP016', 'Chuột không dây LOGITECH SIGNATURE M650', 'DM06', 'NCC03', '630000.00', 10, 'Kết nối không dây linh hoạt, Tích hợp công nghệ SilentTouch', 'Chuot-khong-day-LOGITECH-SIGNATURE-M650.png', 'Còn hàng', 5, 'SYSTEM', NOW()),
('SP017', 'Chuột Gaming có dây LOGITECH G102 LightSync', 'DM06', 'NCC02', '395000.00', 15, 'Trang bị cảm biến gaming cấp độ cao với độ nhạy lên đến 8.000 DPI, Đèn RGB với công nghệ LIGHTSYNC', 'Chuot-Gaming-co-day-LOGITECH-G102-LightSync.png', 'Còn hàng', 5, 'SYSTEM', NOW()),
('SP018', 'Bàn phím cơ có dây Newmen GM328', 'DM07', 'NCC05', '565000.00', 15, 'Cảm giác gõ phím êm dịu, có độ bền cao, Hệ thống đèn LED ấn tượng', 'Ban-phim-co-co-day-Newmen-GM328.png', 'Còn hàng', 5, 'SYSTEM', NOW()),
('SP019', 'Bàn phím Bluetooth Newmen BK66 Foldable', 'DM07', 'NCC04', '1145000.00', 10, 'Tích hợp touchpad, Thiết kế gập ba khúc (foldable) giúp bàn phím trở nên cực kỳ nhỏ gọn khi mang đi', 'Ban-phim-Bluetooth-Newmen-BK66-Foldable.png', 'Còn hàng', 5, 'SYSTEM', NOW()),
('SP020', 'Bàn phím cơ AKKO 3068B Pincess Switch', 'DM07', 'NCC02', '1999000.00', 10, 'Kết nối đa chế độ, Keycap chất lượng, Pin dung lượng khoảng 1800 mAh', 'Ban-phim-co-AKKO-3068B-Pincess-Switch.png', 'Còn hàng', 3, 'SYSTEM', NOW());

INSERT INTO KhuyenMai (MaKM, MaCode, TenKM, PhanTramGiam, TienGiamToiDa, DonToiThieu, SoLuong, NgayBatDau, NgayKetThuc, TrangThai, LoaiKM) VALUES
('KM001', 'GIAM10', 'Giảm 10% cho đơn từ 200.000đ', 10, '50000.00', '200000.00', 100, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), 1, 'NHAP_MA'),
('KM002', 'FREESHIP20', 'Giảm 20% tối đa 100.000đ', 20, '100000.00', '500000.00', 50, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), 1, 'NHAP_MA');

