@extends('layouts.app')

@section('title', 'เพิ่มคูปองใหม่')

@section('content')
<style>
    .coupon-form-container {
        max-width: 700px;
        margin: 40px auto;
        padding: 0 20px;
    }
    .coupon-form-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #FFE8D6;
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
        display: inline-block;
    }
    .link-back:hover { color: #c59b27; }
    .form-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #FFE8D6;
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
        border-color: #FF8C42;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(255,140,66,0.12);
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
        border-top: 1px solid #FFF1E3;
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
        background: linear-gradient(135deg, #FF8C42, #FFB877);
        color: #fff;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(224,106,32,0.25);
        transition: all 0.3s ease;
    }
    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(224,106,32,0.35);
    }
    .switch-container {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }
    .switch-text { color: #4a3423; font-weight: 500; }
    .help-text { font-size: 0.82rem; color: #a38974; margin-top: 4px; }
</style>

<div class="coupon-form-container">
    <a href="{{ route('coupons.index') }}" class="link-back">⬅️ กลับรายการคูปอง</a>

    <div class="coupon-form-header">
        <h2>🎟️ เพิ่มคูปองส่วนลดใหม่</h2>
    </div>

    @if($errors->any())
        <div class="alert-danger">
            <ul class="error-list">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('coupons.store') }}" method="POST" class="form-card">
        @csrf

        <div class="form-group">
            <label>รหัสคูปอง <span style="color: red;">*</span></label>
            <input type="text" name="code" class="form-control" placeholder="เช่น WELCOME20, SAVE50" value="{{ old('code') }}" required style="text-transform: uppercase;">
            <span class="help-text">รหัสที่ลูกค้าจะใส่เพื่อรับส่วนลด</span>
            @error('code') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>ประเภทส่วนลด <span style="color: red;">*</span></label>
                <select name="discount_type" class="form-control" required>
                    <option value="" disabled {{ old('discount_type') ? '' : 'selected' }}>-- เลือก --</option>
                    <option value="percent" {{ old('discount_type') === 'percent' ? 'selected' : '' }}>ลดเป็น % (เปอร์เซ็นต์)</option>
                    <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>ลดเป็นเงิน (บาท)</option>
                </select>
                @error('discount_type') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>มูลค่าส่วนลด <span style="color: red;">*</span></label>
                <input type="number" name="discount_value" class="form-control" step="0.01" min="0" placeholder="0" value="{{ old('discount_value') }}" required>
                @error('discount_value') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>ยอดสั่งซื้อขั้นต่ำ (บาท)</label>
                <input type="number" name="min_order_amount" class="form-control" step="0.01" min="0" placeholder="ไม่มี" value="{{ old('min_order_amount') }}">
            </div>
            <div class="form-group">
                <label>จำกัดส่วนลดสูงสุด (บาท)</label>
                <input type="number" name="max_discount_amount" class="form-control" step="0.01" min="0" placeholder="ไม่จำกัด" value="{{ old('max_discount_amount') }}">
            </div>
        </div>

        <div class="form-group">
            <label>จำกัดจำนวนการใช้ (ครั้ง)</label>
            <input type="number" name="usage_limit" class="form-control" min="1" placeholder="ไม่จำกัด" value="{{ old('usage_limit') }}">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>วันเริ่มใช้งาน</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
            </div>
            <div class="form-group">
                <label>วันหมดอายุ</label>
                <input type="date" name="expire_date" class="form-control" value="{{ old('expire_date') }}">
                @error('expire_date') <span class="text-danger">⚠️ {{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group" style="margin-top: 10px;">
            <label class="switch-container">
                <input type="checkbox" name="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #FF8C42; cursor: pointer;">
                <span class="switch-text">🟢 เปิดใช้งานคูปองนี้ทันที</span>
            </label>
        </div>

        <div class="form-actions">
            <a href="{{ route('coupons.index') }}" class="btn-cancel">ยกเลิก</a>
            <button type="submit" class="btn-submit">💾 บันทึกคูปอง</button>
        </div>
    </form>
</div>
@endsection
