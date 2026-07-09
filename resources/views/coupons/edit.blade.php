@extends('layouts.admin')

@section('title', 'แก้ไขคูปอง')

@section('content')
<style>
    /* ===== STYLE SPECIFIC FOR COUPON EDIT PAGE ===== */
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

    /* 🔴 ไฮไลท์กรอบสีแดงเมื่อข้อมูลผิดพลาดหรือไม่ครบ */
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
</style>

<div class="coupon-form-container">
    <a href="{{ route('coupons.index') }}" class="link-back">
        <i class="bi bi-arrow-left-short"></i> กลับรายการคูปอง
    </a>

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

        <div class="form-group">
            <label>รหัสคูปอง <span style="color: red;">*</span></label>
            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $coupon->code) }}" required style="text-transform: uppercase;">
            @error('code') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>ประเภทส่วนลด <span style="color: red;">*</span></label>
                <select name="discount_type" class="form-control @error('discount_type') is-invalid @enderror" required>
                    <option value="percent" {{ old('discount_type', $coupon->discount_type) === 'percent' ? 'selected' : '' }}>ลดเป็น % (เปอร์เซ็นต์)</option>
                    <option value="fixed" {{ old('discount_type', $coupon->discount_type) === 'fixed' ? 'selected' : '' }}>ลดเป็นเงิน (บาท)</option>
                </select>
                @error('discount_type') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>มูลค่าส่วนลด <span style="color: red;">*</span></label>
                <input type="number" name="discount_value" class="form-control @error('discount_value') is-invalid @enderror" step="0.01" min="0" value="{{ old('discount_value', $coupon->discount_value) }}" required>
                @error('discount_value') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>ยอดสั่งซื้อขั้นต่ำ (บาท)</label>
                <input type="number" name="min_order_amount" class="form-control @error('min_order_amount') is-invalid @enderror" step="0.01" min="0" placeholder="ไม่มี" value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                @error('min_order_amount') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>จำกัดส่วนลดสูงสุด (บาท)</label>
                <input type="number" name="max_discount_amount" class="form-control @error('max_discount_amount') is-invalid @enderror" step="0.01" min="0" placeholder="ไม่จำกัด" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}">
                @error('max_discount_amount') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>จำกัดจำนวนการใช้ (ครั้ง)</label>
            <input type="number" name="usage_limit" class="form-control @error('usage_limit') is-invalid @enderror" min="1" placeholder="ไม่จำกัด" value="{{ old('usage_limit', $coupon->usage_limit) }}">
            @error('usage_limit') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>วันเริ่มใช้งาน</label>
                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $coupon->start_date?->format('Y-m-d')) }}">
                @error('start_date') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
            </div>
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

<script>
    // ดักจับแป้นพิมพ์บล็อกไม่ให้ผู้ใช้งานกรอกตัวอักษร e, E, +, - ลงในช่องตัวเลขทั้งหมด
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '-') {
                e.preventDefault();
            }
        });
    });
</script>
@endsection