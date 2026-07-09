@extends('layouts.admin')

@section('title', 'เพิ่มเมนูใหม่')

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

    .menu-create-container {
        max-width: 700px;
        margin: 0 auto;
    }
    
    .menu-create-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e8dfd8;
    }
    
    .menu-create-header h2 {
        color: #4a3423;
        font-size: 1.5rem;
        margin: 0;
        font-weight: 600;
    }

    .form-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        border: 1px solid #f0e6dd;
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

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
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.1);
    }

    /* 🔴 ไฮไลท์กรอบสีแดงเมื่อข้อมูลผิดพลาดหรือไม่ครบ */
    .form-control.is-invalid {
        border-color: #c5221f;
        background-color: #fff8f8;
    }
    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(197, 34, 31, 0.15);
        border-color: #c5221f;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%236f4e37' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 16px;
        padding-right: 40px;
    }

    .input-group-text {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #a38974;
        font-weight: bold;
    }

    .text-danger {
        color: #c5221f;
        font-size: 0.85rem;
        margin-top: 5px;
        display: block;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #f5eee6;
    }

    .btn {
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 0.95rem;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-cancel {
        background-color: #f4ece6;
        color: #8c6239;
    }

    .btn-cancel:hover {
        background-color: #e8dfd8;
    }

    .btn-submit {
        background-color: #6f4e37;
        color: #ffffff;
        box-shadow: 0 4px 6px rgba(111, 78, 55, 0.15);
    }

    .btn-submit:hover {
        background-color: #523928;
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(111, 78, 55, 0.25);
    }

    .file-preview-box {
        margin-top: 10px;
        display: none;
    }
    .file-preview-box img {
        max-width: 150px;
        border-radius: 8px;
        border: 1px solid #dfcfbe;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .switch-container {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        user-select: none;
    }
    .switch-text {
        font-size: 0.95rem;
        color: #4a3423;
        font-weight: 500;
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
            <li class="sidebar-menu-item active">
                <a href="{{ route('admin.menu.index') }}">
                    <i class="bi bi-egg-fried"></i> <span>จัดการเมนูสินค้า</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
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

    <main class="admin-main-content">
        <div class="menu-create-container">
            <div class="menu-create-header">
                <h2>➕ เพิ่มเมนูร้านกาแฟใหม่</h2>
            </div>

            {{-- ฟอร์มการสร้างเมนู --}}
            <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" class="form-card">
                @csrf

                {{-- 1. ชื่อเมนู --}}
                <div class="form-group">
                    <label for="name">ชื่อเมนู <span class="required-star">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="เช่น เอสเพรสโซ่เย็น, เค้กช็อกโกแลต" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="text-danger">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- 2. หมวดหมู่สินค้า --}}
                <div class="form-group">
                    <label for="category">หมวดหมู่ <span class="required-star">*</span></label>
                    <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
                        <option value="" disabled selected>-- เลือกหมวดหมู่เมนู --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <span class="text-danger">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- 3. ราคา --}}
                <div class="form-group">
                    <label for="price">ราคาขาย (บาท) <span class="required-star">*</span></label>
                    <div style="position: relative;">
                        <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" placeholder="0.00" step="0.01" min="0" value="{{ old('price') }}" required>
                        <span class="input-group-text">฿</span>
                    </div>
                    @error('price')
                        <span class="text-danger">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- 4. คำอธิบายรายละเอียดเมนู --}}
                <div class="form-group">
                    <label for="description">รายละเอียดเมนู</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="ระบุรายละเอียด เช่น ความเข้มข้น เมล็ดกาแฟที่ใช้ หรือระดับความหวาน...">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                {{-- 5. อัปโหลดรูปภาพสินค้า --}}
                <div class="form-group">
                    <label for="image">รูปภาพเมนู</label>
                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" onchange="previewImage(this)">
                    @error('image')
                        <span class="text-danger">⚠️ {{ $message }}</span>
                    @enderror
                    
                    {{-- ตัวแสดงผลตัวอย่างรูปภาพเมื่อเลือกไฟล์ --}}
                    <div class="file-preview-box" id="preview-box">
                        <p style="font-size: 0.85rem; color: #a38974; margin: 5px 0;">ตัวอย่างรูปภาพ:</p>
                        <img id="image-render" src="#" alt="Preview">
                    </div>
                </div>

                {{-- 6. Status พร้อมขาย --}}
                <div class="form-group" style="margin-top: 25px;">
                    <label class="switch-container">
                        <input type="checkbox" name="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #6f4e37; cursor: pointer;">
                        <span class="switch-text">🟢 เปิดขายเมนูนี้ทันที (Available)</span>
                    </label>
                </div>

                {{-- ปุ่มกดยืนยันหรือยกเลิก --}}
                <div class="form-actions">
                    <a href="{{ route('admin.menu.index') }}" class="btn btn-cancel">ยกเลิก</a>
                    <button type="submit" class="btn btn-submit">💾 บันทึกเมนู</button>
                </div>
            </form>
        </div>
    </main>

</div>

{{-- 🛠️ แก้ไขและรวมสคริปต์ JavaScript ให้ถูกต้องเป็นระเบียบ ไม่ซ้อนพัง --}}
<script>
    // 1. ดักจับแป้นพิมพ์บล็อกไม่ให้กรอกตัวอักษร e, E, +, - ลงในช่อง Input ตัวเลขทั้งหมด
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '-') {
                e.preventDefault();
            }
        });
    });

    // 2. โชว์ตัวอย่างรูปภาพสดทันทีหลังกดเลือกไฟล์รูปภาพ
    function previewImage(input) {
        const previewBox = document.getElementById('preview-box');
        const imageRender = document.getElementById('image-render');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imageRender.src = e.target.result;
                previewBox.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            previewBox.style.display = 'none';
        }
    }
</script>
@endsection