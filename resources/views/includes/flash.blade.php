@foreach (['success' => ['success', 'check-circle'], 'error' => ['danger', 'exclamation-circle'], 'warning' => ['warning', 'exclamation-triangle']] as $key => [$class, $icon])
    @if (session($key))
        <div class="alert alert-{{ $class }} alert-dismissible fade show" role="alert">
            <i class="fas fa-{{ $icon }} me-2"></i> <span>{{ session($key) }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif
@endforeach
{{-- Lỗi nghiệp vụ ném từ service (giỏ hàng, đặt hàng) --}}
@foreach (['gioHang', 'donHang'] as $key)
    @error($key)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <span>{{ $message }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @enderror
@endforeach
