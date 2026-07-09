@extends('layouts.app')

@section('title', 'รายละเอียดเมนู')

@section('content')
<style>
    .details-page-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }
    .details-nav-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .link-back {
        color: #6f4e37;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }
    .link-back:hover { color: #c59b27; }
    .btn-warning {
        background-color: #fff3cd;
        color: #856404;
        padding: 8px 18px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-warning:hover { background-color: #ffeeba; transform: translateY(-1px); }
    .details-card-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #f0e6dd;
        overflow: hidden;
    }
    .details-image-section {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 300px;
        background: #faf6f0;
    }
    .menu-large-image {
        width: 100%;
        height: 100%;
        min-height: 300px;
        object-fit: cover;
    }
    .menu-large-no-img {
        text-align: center;
        color: #a38974;
        padding: 40px;
    }
    .emoji-placeholder { font-size: 5rem; display: block; margin-bottom: 10px; }
    .details-info-section { padding: 30px; display: flex; flex-direction: column; gap: 20px; }
    .badge-category-large {
        background-color: #f3ece6;
        color: #8c6239;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .menu-detail-title { color: #4a3423; font-size: 1.6rem; font-weight: 700; margin: 12px 0 6px; }
    .menu-detail-price { color: #6f4e37; font-size: 1.4rem; font-weight: 800; }
    .info-block-title { color: #6f4e37; font-weight: 600; margin-bottom: 6px; font-size: 0.95rem; }
    .menu-detail-description { color: #666; line-height: 1.6; }
    .status-label-text { color: #4a3423; font-weight: 600; margin-right: 8px; }
    .status-badge-large {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }
    .status-badge-large.available { background-color: #e6f4ea; color: #137333; }
    .status-badge-large.unavailable { background-color: #fce8e6; color: #c5221f; }
    @media (max-width: 768px) {
        .details-card-wrapper { grid-template-columns: 1fr; }
    }
</style>

<div class="details-page-container">
    <div class="details-nav-header">
        <a href="{{ route('admin.menu.index') }}" class="link-back">⬅️ กลับหน้าหลักตั้งค่าเมนู</a>
        <a href="{{ route('admin.menu.edit', $product->id) }}" class="btn-warning">✏️ แก้ไขเมนูนี้</a>
    </div>

    <div class="details-card-wrapper">
        {{-- โซนแสดงรูปภาพขนาดใหญ่ --}}
        <div class="details-image-section">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="menu-large-image">
            @else
                <div class="menu-large-no-img">
                    <span class="emoji-placeholder">☕</span>
                    <p>ไม่มีรูปภาพประกอบเมนู</p>
                </div>
            @endif
        </div>

        {{-- โซนแสดงรายละเอียดข้อความข้อมูล --}}
        <div class="details-info-section">
            <div class="info-top">
                <span class="badge-category-large">{{ $product->category }}</span>
                <h1 class="menu-detail-title">{{ $product->name }}</h1>
                <div class="menu-detail-price">฿{{ number_format($product->price, 2) }}</div>
            </div>

            <div class="info-middle">
                <h5 class="info-block-title">รายละเอียด / คำอธิบายสินค้า</h5>
                <p class="menu-detail-description">{{ $product->description ?? 'ไม่มีข้อมูลรายละเอียดเชิงลึกสำหรับเมนูนี้' }}</p>
            </div>

            <div class="info-bottom">
                <span class="status-label-text">สถานะการขายปัจจุบัน:</span>
                {{-- 🛠️ ปรับแก้ให้เช็กค่าเงื่อนไขตรงตามฐานข้อมูลตัวเลข 1 และ 0 ของเพื่อน --}}
                @if($product->status == 1)
                    <span class="status-badge-large available">🟢 พร้อมจำหน่ายบนหน้าจอ POS</span>
                @else
                    <span class="status-badge-large unavailable">🔴 สินค้าหมดชั่วคราว</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection