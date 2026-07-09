@extends('layouts.admin')

@section('title', 'แก้ไขคูปอง')

@section('content')
{{-- เพิ่ม Link สำหรับเรียกใช้งานไอคอนสวยๆ จาก Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* ===== COFFEE ADMIN LAYOUT WITH SIDEBAR ===== */
    .admin-layout-wrapper {
        display: flex;
        min-height: 100vh;
        font-family: 'Sarabun', sans-serif;
        background-color: #fdfbf9;
    }

    /* 📌 Sidebar Component */
    .admin-sidebar {
        width: 260px;
        background-color: #4a3423;
        color: #fcede2;
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 100;
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
    }

    .sidebar-brand {
        padding: 24px;
        font-size: 1.3rem;
        font-weight: 700;
        color: #ffffff;
        border-bottom: 1px solid #5c4331;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar-user-profile {
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid #5c4331;
        background-color: #432e1f;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background-color: #6f4e37;
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: bold;
    }

    .user-info-text .user-name {
        font-weight: 600;
        font-size: 0.95rem;
        margin: 0;
        color: #ffffff;
    }

    .user-info-text .user-role {
        font-size: 0.8rem;
        color: #c4ab97;
        margin: 2px 0 0 0;
    }

    .sidebar-menu-list {
        list-style: none;
        padding: 15px 0;
        margin: 0;
        flex: 1;
    }

    .sidebar-menu-item a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 24px;
        color: #d1bfae;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .sidebar-menu-item a:hover {
        color: #ffffff;
        background-color: #5c4331;
    }

    .sidebar-menu-item.active a {
        color: #ffffff;
        background-color: #6f4e37;
        font-weight: 600;
        border-left: 4px solid #c59b27;
    }

    .sidebar-footer-btn {
        padding: 20px 24px;
        border-top: 1px solid #5c4331;
    }

    .btn-sidebar-logout {
        width: 100%;
        background-color: transparent;
        border: 1px solid #8a684e;
        color: #d1bfae;
        padding: 10px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-sidebar-logout:hover {
        background-color: #c5221f;
        color: #ffffff;
        border-color: #c5221f;
    }

    /* 📌 Main Content Container */
    .admin-main-content {
        flex: 1;
        margin-left: 260px; 
        padding: 40px;
        min-width: 0;
    }

    .coupon-form-container {
        max-width: 700px;
        margin: 0 auto;
    }
    .coupon-form-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e8dfd8;
    }
    .coupon-form-header h2 {
        color: #4a3423;
        font-size: 1.5rem;
        margin: 0;
        font-weight: 700;
    }
    .link-back {
        color: #6f4e37;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .link-back:hover { color: #c59b27; }
    
    .form-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        border: 1px solid #f0e6dd;
        border-top: 4px solid #6f4e37;
        padding: 30px;
    }
    .form-group { margin-bottom: 20px; }
    .form-group label {
        display: block;
        color: #4a3423;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #dfcfbe;
        border-radius: 8px;
        font-size: 0.95rem;
        color: #4a4a4a;
        background-color: #fdfbf9;
        box-sizing: border-box;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        outline: none;
        border-color: #6f4e37;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(111,78,55,0.1);
    }

    /* 🔴 🛠️ จุดเพิ่มสไตล์กรอบสีแดงเมื่อข้อมูลผิดพลาดหรือไม่ครบ */
    .form-control.is-invalid {
        border-color: #c5221f;
        background-color: #fff8f8;
    }
    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(197, 34, 31, 0.15);
        border-color: #c5221f;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .text-danger {
        color: #c5221f;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }
    .alert-danger {
        background-color: #fce8e6;
        border: 1px solid #f5c6cb;
        color: #c5221f;
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .error-list { margin: 0; padding-left: 20px; }
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #f5eee6;
    }
    .btn-cancel {
        background: #f4ece6;
        color: #8c6239;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }
    .btn-cancel:hover { background: #e8dfd8; }
    .btn-submit {
        background: #6f4e37;
        color: #fff;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(111, 78, 55, 0.15);
        transition: all 0.3s ease;
    }
    .btn-submit:hover {
        background-color: #523928;
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(111, 78, 55, 0.25);
    }
    .switch-container {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }
    .switch-text { color: #4a3423; font-weight: 500; }
    .usage-info {
        background: #fcf9f6;
        border: 1px solid #e8dfd8;
        border-radius: 8px;
        padding: 10px 16px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        color: #6f4e37;
    }

    @media (max-width: 992px) {
        .admin-sidebar { width: 70px; }
        .sidebar-brand span, .sidebar-user-profile .user-info-text, .sidebar-menu-item a span, .btn-sidebar-logout span {
            display: none;
        }
        .admin-main-content { margin-left: 70px; padding: 20px; }
        .sidebar-brand, .sidebar-menu-list a, .sidebar-user-profile { justify-content: center; }
    }
</style>

<div class="admin-layout-wrapper">

    <!-- 📌 Left Sidebar Navigation -->
    <nav class="admin-sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-cup-hot-fill" style="color: #c59b27;"></i>
            <span>Coffee Admin</span>
        </div>

        <div class="sidebar-user-profile">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="user-info-text">
                <p class="user-name">{{ Auth::user()->name ?? 'ผู้ดูแลระบบ' }}</p>
                <p class="user-role">Administrator</p>
            </div>
        </div>

        <ul class="sidebar-menu-list">
            <li class="sidebar-menu-item">
                <a href="{{ route('orders.index') }}">
                    <i class="bi bi-speedometer2"></i> <span>หน้าแรก POS</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="{{ route('admin.menu.index') }}">
                    <i class="bi bi-egg-fried"></i> <span>จัดการเมนูสินค้า</span>
                </a>
            </li>
            <li class="sidebar-menu-item active">
                <a href="{{ route('coupons.index') }}">
                    <i class="bi bi-ticket-perforated"></i> <span>จัดการคูปองส่วนลด</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer-btn">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-sidebar-logout">
                    <i class="bi bi-box-arrow-left"></i> <span>ออกจากระบบ</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- 📌 Right Main Content Area -->
    <main class="admin-main-content">
        <div class="coupon-form-container">

            <div class="coupon-form-header">
                <h2>✏️ แก้ไขคูปอง: <strong>{{ $coupon->code }}</strong></h2>
            </div>

            <div class="usage-info">
                <i class="bi bi-bar-chart-line"></i> คูปองนี้ถูกใช้ไปแล้ว <strong>{{ $coupon->used_count }}</strong> ครั้ง
                @if($coupon->usage_limit)
                    จากทั้งหมด <strong>{{ $coupon->usage_limit }}</strong> ครั้ง
                @endif
            </div>

            @if($errors->any())
                <div class="alert-danger">
                    <ul class="error-list">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('coupons.update', $coupon->id) }}" method="POST" class="form-card">
                @csrf
                @method('PUT')

                {{-- 🛠️ ผูกคลาส @error('code') เพื่อเปลี่ยนกรอบเป็นสีแดง --}}
                <div class="form-group">
                    <label>รหัสคูปอง <span style="color: red;">*</span></label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $coupon->code) }}" required style="text-transform: uppercase;">
                    @error('code') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    {{-- 🛠️ ผูกคลาส @error('discount_type') เพื่อเปลี่ยนกรอบเป็นสีแดง --}}
                    <div class="form-group">
                        <label>ประเภทส่วนลด <span style="color: red;">*</span></label>
                        <select name="discount_type" class="form-control @error('discount_type') is-invalid @enderror" required>
                            <option value="percent" {{ old('discount_type', $coupon->discount_type) === 'percent' ? 'selected' : '' }}>ลดเป็น % (เปอร์เซ็นต์)</option>
                            <option value="fixed" {{ old('discount_type', $coupon->discount_type) === 'fixed' ? 'selected' : '' }}>ลดเป็นเงิน (บาท)</option>
                        </select>
                        @error('discount_type') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
                    </div>
                    {{-- 🛠️ ผูกคลาส @error('discount_value') เพื่อเปลี่ยนกรอบเป็นสีแดง --}}
                    <div class="form-group">
                        <label>มูลค่าส่วนลด <span style="color: red;">*</span></label>
                        <input type="number" name="discount_value" class="form-control @error('discount_value') is-invalid @enderror" step="0.01" min="0" value="{{ old('discount_value', $coupon->discount_value) }}" required>
                        @error('discount_value') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    {{-- 🛠️ ผูกคลาส @error('min_order_amount') เพื่อเปลี่ยนกรอบเป็นสีแดง --}}
                    <div class="form-group">
                        <label>ยอดสั่งซื้อขั้นต่ำ (บาท)</label>
                        <input type="number" name="min_order_amount" class="form-control @error('min_order_amount') is-invalid @enderror" step="0.01" min="0" placeholder="ไม่มี" value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                        @error('min_order_amount') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
                    </div>
                    {{-- 🛠️ ผูกคลาส @error('max_discount_amount') เพื่อเปลี่ยนกรอบเป็นสีแดง --}}
                    <div class="form-group">
                        <label>จำกัดส่วนลดสูงสุด (บาท)</label>
                        <input type="number" name="max_discount_amount" class="form-control @error('max_discount_amount') is-invalid @enderror" step="0.01" min="0" placeholder="ไม่จำกัด" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}">
                        @error('max_discount_amount') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- 🛠️ ผูกคลาส @error('usage_limit') เพื่อเปลี่ยนกรอบเป็นสีแดง --}}
                <div class="form-group">
                    <label>จำกัดจำนวนการใช้ (ครั้ง)</label>
                    <input type="number" name="usage_limit" class="form-control @error('usage_limit') is-invalid @enderror" min="1" placeholder="ไม่จำกัด" value="{{ old('usage_limit', $coupon->usage_limit) }}">
                    @error('usage_limit') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    {{-- 🛠️ ผูกคลาส @error('start_date') เพื่อเปลี่ยนกรอบเป็นสีแดง --}}
                    <div class="form-group">
                        <label>วันเริ่มใช้งาน</label>
                        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $coupon->start_date?->format('Y-m-d')) }}">
                        @error('start_date') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
                    </div>
                    {{-- 🛠️ ผูกคลาส @error('expire_date') เพื่อเปลี่ยนกรอบเป็นสีแดง --}}
                    <div class="form-group">
                        <label>วันหมดอายุ</label>
                        <input type="date" name="expire_date" class="form-control @error('expire_date') is-invalid @enderror" value="{{ old('expire_date', $coupon->expire_date?->format('Y-m-d')) }}">
                        @error('expire_date') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top: 10px;">
                    <label class="switch-container">
                        <input type="checkbox" name="status" value="1" {{ old('status', $coupon->status) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #6f4e37; cursor: pointer;">
                        <span class="switch-text">🟢 เปิดใช้งานคูปองนี้</span>
                    </label>
                </div>

                <div class="form-actions">
                    <a href="{{ route('coupons.index') }}" class="btn-cancel">ยกเลิก</a>
                    <button type="submit" class="btn-submit">💾 อัปเดตคูปอง</button>
                </div>
            </form>
        </div>
    </main>

</div>

{{-- 🛠️ [เพิ่มชุดสคริปต์] ดักจับแป้นพิมพ์บล็อกไม่ให้ผู้ใช้งานกรอกตัวอักษร e, E, +, - ลงในช่องตัวเลขทั้งหมด --}}
<script>
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '-') {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
