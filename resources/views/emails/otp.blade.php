<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif">
<div style="max-width:600px;margin:0 auto;padding:20px">
    <h2>🔐 Đặt lại mật khẩu</h2>
    <p>Bạn đã yêu cầu đặt lại mật khẩu. Sử dụng mã OTP sau để tiếp tục:</p>
    <div style="background:#007bff;color:#fff;padding:20px;text-align:center;font-size:24px;font-weight:bold;border-radius:5px;margin:20px 0">{{ $otp }}</div>
    <p>Mã OTP này sẽ hết hạn sau <strong>{{ $soPhut }} phút</strong>.</p>
    <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
    <p>Trân trọng,<br>{{ config('app.name') }}</p>
</div>
</body>
</html>
