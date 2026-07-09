@extends('layouts.app')

@section('title', 'แก้ไขเมนู')

@section('content')
<style>
    .form-page-container {
        max-width: 700px;
        margin: 40px auto;
        padding: 0 20px;
    }
    .back-nav { margin-bottom: 20px; }
    .link-back {
        color: #6f4e37;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }
    .link-back:hover { color: #c59b27; }
    .form-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
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
        box-shadow: 0 4px 6px rgba(111,78,55,0.15);
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 10px;
    }
    .btn-update-menu:hover {
        background-color: #523928;
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(111,78,55,0.25);
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
</style>

<div class="form-page-container">
    <div class="back-nav">
        <a href="{{ route('admin.menu.index') }}" class="link-back">⬅️ กลับหน้าหลักตั้งค่าเมนู</a>
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

            <div class="form-group">
                <label class="form-label">ชื่อเมนู <span class="required-star">*</span></label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">หมวด หมู่ สินค้า <span class="required-star">*</span></label>
                <select name="category" class="form-select" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category', $product->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">ราคาขาย (บาท) <span class="required-star">*</span></label>
                <input type="number" name="price" step="0.01" min="0" class="form-input" value="{{ old('price', $product->price) }}" required>
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
@endsection