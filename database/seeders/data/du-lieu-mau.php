<?php

// Dữ liệu mẫu trích từ database/QL_PhuKienDienTu_MySQL.sql (mật khẩu mặc định: 123456)
return array (
  'TaiKhoan' => 
  array (
    0 => 
    array (
      'TenDangNhap' => 'admin',
      'MatKhau' => '$2y$10$LFnMD5B9oCScnnXQkXHbpeS.KIQIyolOB4/Xoc0xPJNQbPV0Gfawy',
      'Quyen' => 'ROLE_ADMIN',
      'TrangThai' => 1,
      'LoaiTaiKhoan' => 'LOCAL',
      'ProviderId' => NULL,
    ),
    1 => 
    array (
      'TenDangNhap' => 'khachhang',
      'MatKhau' => '$2y$10$LFnMD5B9oCScnnXQkXHbpeS.KIQIyolOB4/Xoc0xPJNQbPV0Gfawy',
      'Quyen' => 'ROLE_CUSTOMER',
      'TrangThai' => 1,
      'LoaiTaiKhoan' => 'LOCAL',
      'ProviderId' => NULL,
    ),
    2 => 
    array (
      'TenDangNhap' => 'nhanvien',
      'MatKhau' => '$2y$10$LFnMD5B9oCScnnXQkXHbpeS.KIQIyolOB4/Xoc0xPJNQbPV0Gfawy',
      'Quyen' => 'ROLE_STAFF',
      'TrangThai' => 1,
      'LoaiTaiKhoan' => 'LOCAL',
      'ProviderId' => NULL,
    ),
  ),
  'NhanVien' => 
  array (
    0 => 
    array (
      'MaNV' => 'NV001',
      'HoTen' => 'Quản Trị Viên',
      'ChucVu' => 'Admin',
      'DienThoai' => '0999888777',
      'TenDangNhap' => 'admin',
    ),
    1 => 
    array (
      'MaNV' => 'NV002',
      'HoTen' => 'Nhân Viên Bán Hàng',
      'ChucVu' => 'Nhân viên',
      'DienThoai' => '0988777666',
      'TenDangNhap' => 'nhanvien',
    ),
  ),
  'KhachHang' => 
  array (
    0 => 
    array (
      'MaKH' => 'KH001',
      'HoTen' => 'Nguyễn Văn A',
      'GioiTinh' => 'Nam',
      'DiaChi' => 'TP.HCM',
      'DienThoai' => '0123456789',
      'Email' => 'a@gmail.com',
      'TenDangNhap' => 'khachhang',
    ),
  ),
  'DanhMuc' => 
  array (
    0 => 
    array (
      'MaDM' => 'DM01',
      'TenDM' => 'Ốp lưng',
    ),
    1 => 
    array (
      'MaDM' => 'DM02',
      'TenDM' => 'Tai nghe',
    ),
    2 => 
    array (
      'MaDM' => 'DM03',
      'TenDM' => 'Sạc dự phòng',
    ),
    3 => 
    array (
      'MaDM' => 'DM04',
      'TenDM' => 'Cáp sạc',
    ),
    4 => 
    array (
      'MaDM' => 'DM05',
      'TenDM' => 'Kính cường lực',
    ),
    5 => 
    array (
      'MaDM' => 'DM06',
      'TenDM' => 'Chuột',
    ),
    6 => 
    array (
      'MaDM' => 'DM07',
      'TenDM' => 'Bàn Phím',
    ),
  ),
  'NhaCungCap' => 
  array (
    0 => 
    array (
      'MaNCC' => 'NCC01',
      'TenNCC' => 'Phụ kiện An Phát',
      'DiaChi' => 'Hà Nội',
      'SoDienThoai' => '0987654321',
      'Email' => 'anphat@gmail.com',
    ),
    1 => 
    array (
      'MaNCC' => 'NCC02',
      'TenNCC' => 'MobileKing',
      'DiaChi' => 'TP.HCM',
      'SoDienThoai' => '0912345678',
      'Email' => 'mobileking@gmail.com',
    ),
    2 => 
    array (
      'MaNCC' => 'NCC03',
      'TenNCC' => 'TechZone',
      'DiaChi' => 'Đà Nẵng',
      'SoDienThoai' => '0905123456',
      'Email' => 'techzone@gmail.com',
    ),
    3 => 
    array (
      'MaNCC' => 'NCC04',
      'TenNCC' => 'Phụ kiện Minh Tâm',
      'DiaChi' => 'Cần Thơ',
      'SoDienThoai' => '0977888999',
      'Email' => 'minhtam@gmail.com',
    ),
    4 => 
    array (
      'MaNCC' => 'NCC05',
      'TenNCC' => 'GadgetPro',
      'DiaChi' => 'Hải Phòng',
      'SoDienThoai' => '0933444555',
      'Email' => 'gadgetpro@gmail.com',
    ),
  ),
  'SanPham' => 
  array (
    0 => 
    array (
      'MaSP' => 'SP001',
      'TenSP' => 'Ốp lưng iPhone 14 trong suốt',
      'MaDM' => 'DM01',
      'MaNCC' => 'NCC01',
      'Gia' => '120000.00',
      'SoLuong' => 5,
      'MoTa' => 'Ốp lưng nhựa dẻo chống sốc',
      'HinhAnh' => 'OpLungiPhone14TrongSuot.jpg',
      'TinhTrang' => 'Sắp hết',
      'NguongToiThieu' => 10,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    1 => 
    array (
      'MaSP' => 'SP002',
      'TenSP' => 'Ốp lưng Samsung S23 chống va đập',
      'MaDM' => 'DM01',
      'MaNCC' => 'NCC01',
      'Gia' => '250000.00',
      'SoLuong' => 30,
      'MoTa' => 'Ốp lưng silicon cao cấp',
      'HinhAnh' => 'OpLungSamSungS23.jpg',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 10,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    2 => 
    array (
      'MaSP' => 'SP003',
      'TenSP' => 'Ốp lưng MagSafe iPhone 15',
      'MaDM' => 'DM01',
      'MaNCC' => 'NCC01',
      'Gia' => '120000.00',
      'SoLuong' => 40,
      'MoTa' => 'Hỗ trợ sạc không dây',
      'HinhAnh' => 'OpLungMagsafeiPhone15.jpg',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 10,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    3 => 
    array (
      'MaSP' => 'SP004',
      'TenSP' => 'Ốp lưng da Galaxy Z Flip 5',
      'MaDM' => 'DM01',
      'MaNCC' => 'NCC01',
      'Gia' => '300000.00',
      'SoLuong' => 25,
      'MoTa' => 'Chất liệu da thật, cao cấp',
      'HinhAnh' => 'OpLungDaGalaxyZFlip5.jpg',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 10,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    4 => 
    array (
      'MaSP' => 'SP005',
      'TenSP' => 'Tai nghe Bluetooth Baseus',
      'MaDM' => 'DM02',
      'MaNCC' => 'NCC01',
      'Gia' => '350000.00',
      'SoLuong' => 60,
      'MoTa' => 'Tai nghe không dây pin 8 giờ',
      'HinhAnh' => 'TaiNgheBluetoothBaseus.jpg',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 5,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    5 => 
    array (
      'MaSP' => 'SP006',
      'TenSP' => 'Tai nghe True Wireless Xiaomi Buds',
      'MaDM' => 'DM02',
      'MaNCC' => 'NCC01',
      'Gia' => '490000.00',
      'SoLuong' => 50,
      'MoTa' => 'Chống ồn chủ động, Bluetooth 5.3',
      'HinhAnh' => 'TaiNgheTrueWirelessXiaomiBuds.jpg',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 5,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    6 => 
    array (
      'MaSP' => 'SP007',
      'TenSP' => 'Tai nghe Apple AirPods 3',
      'MaDM' => 'DM02',
      'MaNCC' => 'NCC01',
      'Gia' => '4500000.00',
      'SoLuong' => 20,
      'MoTa' => 'Âm thanh không gian, cảm biến lực',
      'HinhAnh' => 'AirPods3.jpg',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 5,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    7 => 
    array (
      'MaSP' => 'SP008',
      'TenSP' => 'Tai nghe Samsung Buds2 Pro',
      'MaDM' => 'DM02',
      'MaNCC' => 'NCC01',
      'Gia' => '4200000.00',
      'SoLuong' => 25,
      'MoTa' => 'Hỗ trợ âm thanh 24-bit Hi-Fi',
      'HinhAnh' => 'SamSungBuds2Pro.jpg',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 5,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    8 => 
    array (
      'MaSP' => 'SP009',
      'TenSP' => 'Sạc dự phòng Romoss 20000mAh Sense8',
      'MaDM' => 'DM03',
      'MaNCC' => 'NCC01',
      'Gia' => '590000.00',
      'SoLuong' => 40,
      'MoTa' => 'Hỗ trợ sạc nhanh PD 18W, 3 cổng ra',
      'HinhAnh' => 'SacDuPhongRomoss20000mAhSense8.jpg',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 5,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    9 => 
    array (
      'MaSP' => 'SP010',
      'TenSP' => 'Sạc dự phòng Innostyle PowerGo 10000mAh',
      'MaDM' => 'DM03',
      'MaNCC' => 'NCC01',
      'Gia' => '690000.00',
      'SoLuong' => 35,
      'MoTa' => 'Thiết kế nhỏ gọn, sạc nhanh QC 3.0',
      'HinhAnh' => 'InnostylePowerGo10000mAh.jpg',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 5,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    10 => 
    array (
      'MaSP' => 'SP011',
      'TenSP' => 'Cáp sạc Baseus Type-C 2m',
      'MaDM' => 'DM04',
      'MaNCC' => 'NCC01',
      'Gia' => '259000.00',
      'SoLuong' => 80,
      'MoTa' => 'Dây bọc dù siêu bền, hỗ trợ PD 60W',
      'HinhAnh' => 'CapSacBaseusType-C2m.jpg',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 15,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    11 => 
    array (
      'MaSP' => 'SP012',
      'TenSP' => 'Cáp sạc Remax Lightning RC-094i',
      'MaDM' => 'DM04',
      'MaNCC' => 'NCC01',
      'Gia' => '119000.00',
      'SoLuong' => 70,
      'MoTa' => 'Chứng nhận MFi, độ bền cao',
      'HinhAnh' => 'CapSacRemaxLightningRC-094i.jpg',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 15,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    12 => 
    array (
      'MaSP' => 'SP013',
      'TenSP' => 'Kính cường lực iPhone 14 Pro Max Nillkin',
      'MaDM' => 'DM05',
      'MaNCC' => 'NCC01',
      'Gia' => '129000.00',
      'SoLuong' => 50,
      'MoTa' => 'Kính Nillkin 9H, chống vân tay, độ trong cao',
      'HinhAnh' => 'CuongLuciPhone14ProMaxNillkin.jpg',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 5,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    13 => 
    array (
      'MaSP' => 'SP014',
      'TenSP' => 'Kính cường lực KingKong',
      'MaDM' => 'DM05',
      'MaNCC' => 'NCC01',
      'Gia' => '309000.00',
      'SoLuong' => 60,
      'MoTa' => 'Chống trầy, phủ nano, cảm ứng nhạy',
      'HinhAnh' => 'CuongLucKingKong.jpg',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 5,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    14 => 
    array (
      'MaSP' => 'SP015',
      'TenSP' => 'Chuột không dây Ugreen Silent MU006',
      'MaDM' => 'DM06',
      'MaNCC' => 'NCC01',
      'Gia' => '180000.00',
      'SoLuong' => 30,
      'MoTa' => 'Kết nối không dây 2.4 GHz với khoảng cách hoạt động lên tới khoảng 15m, Thiết kế công thái học',
      'HinhAnh' => 'Chuot-khong-day-Ugreen-Silent-MU006.png',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 5,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    15 => 
    array (
      'MaSP' => 'SP016',
      'TenSP' => 'Chuột không dây LOGITECH SIGNATURE M650',
      'MaDM' => 'DM06',
      'MaNCC' => 'NCC03',
      'Gia' => '630000.00',
      'SoLuong' => 10,
      'MoTa' => 'Kết nối không dây linh hoạt, Tích hợp công nghệ SilentTouch',
      'HinhAnh' => 'Chuot-khong-day-LOGITECH-SIGNATURE-M650.png',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 5,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    16 => 
    array (
      'MaSP' => 'SP017',
      'TenSP' => 'Chuột Gaming có dây LOGITECH G102 LightSync',
      'MaDM' => 'DM06',
      'MaNCC' => 'NCC02',
      'Gia' => '395000.00',
      'SoLuong' => 15,
      'MoTa' => 'Trang bị cảm biến gaming cấp độ cao với độ nhạy lên đến 8.000 DPI, Đèn RGB với công nghệ LIGHTSYNC',
      'HinhAnh' => 'Chuot-Gaming-co-day-LOGITECH-G102-LightSync.png',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 5,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    17 => 
    array (
      'MaSP' => 'SP018',
      'TenSP' => 'Bàn phím cơ có dây Newmen GM328',
      'MaDM' => 'DM07',
      'MaNCC' => 'NCC05',
      'Gia' => '565000.00',
      'SoLuong' => 15,
      'MoTa' => 'Cảm giác gõ phím êm dịu, có độ bền cao, Hệ thống đèn LED ấn tượng',
      'HinhAnh' => 'Ban-phim-co-co-day-Newmen-GM328.png',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 5,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    18 => 
    array (
      'MaSP' => 'SP019',
      'TenSP' => 'Bàn phím Bluetooth Newmen BK66 Foldable',
      'MaDM' => 'DM07',
      'MaNCC' => 'NCC04',
      'Gia' => '1145000.00',
      'SoLuong' => 10,
      'MoTa' => 'Tích hợp touchpad, Thiết kế gập ba khúc (foldable) giúp bàn phím trở nên cực kỳ nhỏ gọn khi mang đi',
      'HinhAnh' => 'Ban-phim-Bluetooth-Newmen-BK66-Foldable.png',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 5,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
    19 => 
    array (
      'MaSP' => 'SP020',
      'TenSP' => 'Bàn phím cơ AKKO 3068B Pincess Switch',
      'MaDM' => 'DM07',
      'MaNCC' => 'NCC02',
      'Gia' => '1999000.00',
      'SoLuong' => 10,
      'MoTa' => 'Kết nối đa chế độ, Keycap chất lượng, Pin dung lượng khoảng 1800 mAh',
      'HinhAnh' => 'Ban-phim-co-AKKO-3068B-Pincess-Switch.png',
      'TinhTrang' => 'Còn hàng',
      'NguongToiThieu' => 3,
      'CreatedBy' => 'SYSTEM',
      'LastModifiedBy' => NULL,
      'LastModifiedDate' => NULL,
    ),
  ),
  'KhuyenMai' => 
  array (
    0 => 
    array (
      'MaKM' => 'KM001',
      'MaCode' => 'GIAM10',
      'TenKM' => 'Giảm 10% cho đơn từ 200.000đ',
      'PhanTramGiam' => 10,
      'TienGiamToiDa' => '50000.00',
      'DonToiThieu' => '200000.00',
      'SoLuong' => 100,
      'TrangThai' => 1,
      'LoaiKM' => 'NHAP_MA',
    ),
    1 => 
    array (
      'MaKM' => 'KM002',
      'MaCode' => 'FREESHIP20',
      'TenKM' => 'Giảm 20% tối đa 100.000đ',
      'PhanTramGiam' => 20,
      'TienGiamToiDa' => '100000.00',
      'DonToiThieu' => '500000.00',
      'SoLuong' => 50,
      'TrangThai' => 1,
      'LoaiKM' => 'NHAP_MA',
    ),
  ),
);
