@extends('layouts.app') {{-- 🛠️ เปลี่ยนเป็นชื่อ Layout หลักของธีมคุณ เช่น layouts.admin --}}

@section('title', 'เพิ่มเมนูใหม่')

@section('content')
<style>
    /* ===== COFFEE THEME CSS FOR CREATE PAGE ===== */
    .menu-create-container {
        max-width: 700px;
        margin: 40px auto;
        padding: 0 20px;
        font-family: 'Sarabun', sans-serif;
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
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
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

    /* สไตล์ตัวอัปโหลดรูปภาพ */
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

    /* สไตล์สำหรับปุ่ม Checkbox สลับเปิด-ปิด */
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
</style>

<div class="menu-create-container">
    <div class="menu-create-header">
        <h2>➕ เพิ่มเมนูร้านกาแฟใหม่</h2>
    </div>

    {{-- ฟอร์มการสร้างเมนู --}}
    <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" class="form-card">
        @csrf

        {{-- 1. ชื่อเมนู --}}
        <div class="form-group">
            <label for="name">ชื่อเมนู <span style="color: red;">*</span></label>
            <input type="text" name="name" id="name" class="form-control" placeholder="เช่น เอสเพรสโซ่เย็น, เค้กช็อกโกแลต" value="{{ old('name') }}" required>
            @error('name')
                <span class="text-danger">⚠️ {{ $message }}</span>
            @enderror
        </div>

        {{-- 2. หมวดหมู่สินค้า (วนลูปดึงตามอาร์เรย์จาก Controller เพื่อความยืดหยุ่น) --}}
        <div class="form-group">
            <label for="category">หมวดหมู <span style="color: red;">*</span></label>
            <select name="category" id="category" class="form-control" required>
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
            <label for="price">ราคาขาย (บาท) <span style="color: red;">*</span></label>
            <div style="position: relative;">
                <input type="number" name="price" id="price" class="form-control" placeholder="0.00" step="0.01" min="0" value="{{ old('price') }}" required>
                <span class="input-group-text">฿</span>
            </div>
            @error('price')
                <span class="text-danger">⚠️ {{ $message }}</span>
            @enderror
        </div>

        {{-- 4. คำอธิบายรายละเอียดเมนู --}}
        <div class="form-group">
            <label for="description">รายละเอียดเมนู</label>
            <textarea name="description" id="description" class="form-control" rows="4" placeholder="ระบุรายละเอียด เช่น ความเข้มข้น เมล็ดกาแฟที่ใช้ หรือระดับความหวาน..." style="resize: vertical;">{{ old('description') }}</textarea>
            @error('description')
                <span class="text-danger">⚠️ {{ $message }}</span>
            @enderror
        </div>

        {{-- 5. อัปโหลดรูปภาพสินค้า --}}
        <div class="form-group">
            <label for="image">รูปภาพเมนู</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*" onchange="previewImage(this)">
            @error('image')
                <span class="text-danger">⚠️ {{ $message }}</span>
            @enderror
            
            {{-- ตัวแสดงผลตัวอย่างรูปภาพเมื่อเลือกไฟล์ --}}
            <div class="file-preview-box" id="preview-box">
                <p style="font-size: 0.85rem; color: #a38974; margin: 5px 0;">ตัวอย่างรูปภาพ:</p>
                <img id="image-render" src="#" alt="Preview">
            </div>
        </div>

        {{-- 6. สถานะพร้อมขาย (เปลี่ยนมาใช้ตัวแปร is_available ตามเงื่อนไข $request->has('is_available') ของคุณ) --}}
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

{{-- สคริปต์ JavaScript โชว์ตัวอย่างรูปภาพสดทันทีหลังกดอัปโหลด --}}
<script>
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