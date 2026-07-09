@extends('layouts.admin')

@section('title', 'จัดการคูปอง')

@section('content')
<style>
    /* ===== STYLE SPECIFIC FOR COUPON INDEX PAGE ===== */
    .coupon-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e8dfd8;
    }
    .coupon-header h2 {
        color: #4a3423;
        font-size: 1.6rem;
        margin: 0;
        font-weight: 600;
    }
    .btn-add-coupon {
        background-color: #6f4e37;
        color: #fff !important;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(111, 78, 55, 0.15);
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-add-coupon:hover {
        background-color: #523928;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(111, 78, 55, 0.25);
    }

    /* 🔔 แจ้งเตือนความสำเร็จ เด้งมุมจอขวาบน (Toast Notification) */
    .alert-success {
        position: fixed;
        top: 25px;
        right: 25px;
        background-color: #f4ebe1;
        border: 1px solid #dfcfbe;
        color: #6f4e37;
        padding: 15px 25px;
        border-radius: 8px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(111, 78, 55, 0.15);
        z-index: 9999;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        animation: slideInRight 0.4s ease-out forwards, fadeOut 0.5s ease-in 3.5s forwards;
    }
    @keyframes slideInRight {
        from { transform: translateX(120%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes fadeOut {
        from { opacity: 1; transform: scale(1); }
        to { opacity: 0; transform: scale(0.9); visibility: hidden; }
    }

    .coupon-table-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        border: 1px solid #f0e6dd;
        overflow: hidden;
    }
    .coupon-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .coupon-table th {
        background-color: #fcf9f6;
        color: #6f4e37;
        font-weight: 600;
        padding: 16px;
        font-size: 0.95rem;
        border-bottom: 2px solid #e8dfd8;
    }
    .coupon-table td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f5eee6;
        color: #4a4a4a;
        font-size: 0.95rem;
    }
    .coupon-table tbody tr:hover { background-color: #faf6f0; }
    
    .coupon-code-badge {
        background-color: #6f4e37;
        color: #fff;
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 700;
        font-family: monospace;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    .discount-text { font-weight: 700; color: #6f4e37; }
    
    .status-active {
        background-color: #e6f4ea;
        color: #137333;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    .status-inactive {
        background-color: #fce8e6;
        color: #c5221f;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    .action-buttons .btn {
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .action-buttons .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
    }
    .btn-edit { background-color: #fff3cd; color: #856404; }
    .btn-edit:hover { background-color: #ffeeba; }
    .btn-delete { background-color: #f8d7da; color: #bd2130; }
    .btn-delete:hover { background-color: #f5c6cb; }
    
    .table-empty {
        padding: 50px !important;
        text-align: center;
        color: #a38974;
        font-size: 1rem;
    }
    .usage-text { font-size: 0.85rem; color: #8c8c8c; font-weight: 500; }
</style>

<div class="coupon-header">
    <h2>🎟️ จัดการคูปองส่วนลด</h2>
    <a href="{{ route('coupons.create') }}" class="btn-add-coupon">
        <i class="bi bi-plus-circle"></i> เพิ่มคูปองใหม่
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <span>🎉 {{ session('success') }}</span>
    </div>
@endif

<div class="coupon-table-card">
    <div class="table-responsive" style="overflow-x: auto;">
        <table class="coupon-table">
            <thead>
                <tr>
                    <th>รหัสคูปอง</th>
                    <th>ส่วนลด</th>
                    <th>ยอดขั้นต่ำ</th>
                    <th>จำกัดสูงสุด</th>
                    <th>ใช้ไปแล้ว</th>
                    <th>วันหมดอายุ</th>
                    <th>สถานะ</th>
                    <th style="text-align: center; width: 180px;">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td><span class="coupon-code-badge">{{ $coupon->code }}</span></td>
                        <td>
                            <span class="discount-text">
                                @if($coupon->discount_type === 'percent')
                                    {{ number_format($coupon->discount_value, 0) }}%
                                @else
                                    ฿{{ number_format($coupon->discount_value, 0) }}
                                @endif
                            </span>
                        </td>
                        <td>{{ $coupon->min_order_amount ? '฿'.number_format($coupon->min_order_amount, 0) : '-' }}</td>
                        <td>{{ $coupon->max_discount_amount ? '฿'.number_format($coupon->max_discount_amount, 0) : '-' }}</td>
                        <td>
                            <span class="usage-text">
                                {{ $coupon->used_count }}{{ $coupon->usage_limit ? '/'.$coupon->usage_limit : '' }} ครั้ง
                            </span>
                        </td>
                        <td>{{ $coupon->expire_date ? $coupon->expire_date->format('d/m/Y') : 'ไม่มีกำหนด' }}</td>
                        <td>
                            @if($coupon->status)
                                <span class="status-active">เปิดใช้งาน</span>
                            @else
                                <span class="status-inactive">ปิดใช้งาน</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-edit" title="แก้ไขคูปอง">
                                    <i class="bi bi-pencil-square"></i> แก้ไข
                                </a>
                                <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('ยืนยันลบคูปองนี้?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete" title="ลบคูปอง">
                                        <i class="bi bi-trash3"></i> ลบ
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="table-empty">
                            🎟️ ยังไม่มีคูปองในระบบ กดปุ่ม "เพิ่มคูปองใหม่" เพื่อเริ่มสร้างคูปองส่วนลด
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection