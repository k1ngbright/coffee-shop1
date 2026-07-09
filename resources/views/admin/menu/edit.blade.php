@extends('layouts.admin')

@section('title', 'แก้ไขเมนู')

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

    .form-page-container {
        max-width: 700px;
        margin: 0 auto;
    }
    .back-nav { margin-bottom: 20px; }
    .link-back {
        color: #6f4e37;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .link-back:hover { color: #c59b27; }
    
    .form-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        border: 1px solid #f0e6dd;
        padding: 30px;
    }
    .form-card.edit-mode { border-top: 4px solid #6f4e37; }
    .form-title {
        color: #4a3423;
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 25px;
    }
    .form-group { margin-bottom: 20px; }
    .form-label {
        display: block;
        color: #4a3423;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }
    .required-star { color: #c5221f; }
    
    .form-input, .form-select, .form-textarea, .form-file-input {
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
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #6f4e37;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(111,78,55,0.1);
    }
    
    /* 🔴 ไฮไลท์กรอบสีแดงเมื่อข้อมูลผิดพลาดหรือไม่ครบ */
    .form-input.is-invalid, .form-select.is-invalid {
        border-color: #c5221f;
        background-color: #fff8f8;
    }
    .form-input.is-invalid:focus, .form-select.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(197, 34, 31, 0.15);
        border-color: #c5221f;
    }
    
    .form-textarea { resize: vertical; }
    
    .form-group-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        margin-top: 10px;
    }
    .form-checkbox { width: 18px; height: 18px; accent-color: #6f4e37; cursor: pointer; }
    .checkbox-label { color: #4a3423; font-weight: 500; cursor: pointer; }
    
    .image-preview-box { margin: 10px 0; }
    .edit-img-preview {
        max-width: 150px;
        border-radius: 8px;
        border: 1px solid #dfcfbe;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .image-help-text { font-size: 0.8rem; color: #a38974; margin-top: 5px; }
    
    .btn-update-menu {
        background-color: #6f4e37;
        color: #fff;
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(111, 78, 55, 0.15);
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 10px;
    }
    .btn-update-menu:hover {
        background-color: #523928;
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(111, 78, 55, 0.25);
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
    .error-list li { margin-bottom: 4px; }

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
        <div class="form-page-container">
            <div class="back-nav">
                <a href="{{ route('admin.menu.index') }}" class="link-back">
                    <i class="bi bi-arrow-left-short"></i> กลับหน้าหลักตั้งค่าเมนู
                </a>
            </div>
            
            <div class="form-card edit-mode">
                <h2 class="form-title">✏️ แก้ไขข้อมูลเมนู</h2>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="error-list">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.menu.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="menu-form">
                    @csrf
                    @method('PUT')

                    {{-- 🛠️ ผูกคลาส @error('name') เพื่อเปิดกรอบสีแดง --}}
                    <div class="form-group">
                        <label class="form-label">ชื่อเมนู <span class="required-star">*</span></label>
                        <input type="text" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                    </div>

                    {{-- 🛠️ ผูกคลาส @error('category') เพื่อเปิดกรอบสีแดง --}}
                    <div class="form-group">
                        <label class="form-label">หมวดหมู่สินค้า <span class="required-star">*</span></label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $product->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 🛠️ ผูกคลาส @error('price') เพื่อเปิดกรอบสีแดง --}}
                    <div class="form-group">
                        <label class="form-label">ราคาขาย (บาท) <span class="required-star">*</span></label>
                        <input type="number" name="price" step="0.01" min="0" class="form-input @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">รายละเอียดคำอธิบายเมนู</label>
                        <textarea name="description" class="form-textarea" rows="3">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="form-group current-image-wrapper">
                        <label class="form-label">รูปภาพประกอบเมนูปัจจุบัน</label>
                        @if($product->image)
                            <div class="image-preview-box">
                                <img src="{{ asset('storage/' . $product->image) }}" class="edit-img-preview" alt="current image">
                                <p class="image-help-text">* หากไม่ต้องการเปลี่ยนรูปภาพ ไม่ต้องเลือกไฟล์ใหม่ด้านล่าง</p>
                            </div>
                        @endif
                        <input type="file" name="image" class="form-file-input" accept="image/*">
                    </div>

                    <div class="form-group-checkbox">
                        <input type="checkbox" id="status" name="status" class="form-checkbox" value="1" {{ old('status', $product->status) ? 'checked' : '' }}>
                        <label for="status" class="checkbox-label">เปิดขายรายการนี้ (แสดงบนหน้าร้าน POS)</label>
                    </div>

                    <button type="submit" class="btn-update-menu">💾 อัปเดตข้อมูลเมนู</button>
                </form>
            </div>
        </div>
    </main>

</div>

{{-- 🛠️ [เพิ่มชุดสคริปต์] ดักจับแป้นพิมพ์บล็อกไม่ให้ผู้ใช้งานกรอกตัวอักษร e, E, +, - ลงในช่องราคาขาย --}}
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