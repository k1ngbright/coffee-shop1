@extends('layouts.app')

@section('title', 'จัดการคูปอง')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .coupon-container {
        max-width: 1140px;
        margin: 40px auto;
        padding: 0 20px;
    }
    .coupon-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #FFE8D6;
    }
    .coupon-header h2 {
        color: #4a3423;
        font-size: 1.6rem;
        margin: 0;
        font-weight: 700;
    }
    .btn-add-coupon {
        background: linear-gradient(135deg, #FF8C42, #FFB877);
        color: #fff !important;
        padding: 10px 22px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(224,106,32,0.25);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-add-coupon:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(224,106,32,0.35);
        color: #fff;
    }
    .alert-success {
        background-color: #f0faf0;
        border: 1px solid #b7e4b7;
        color: #137333;
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        font-weight: 500;
    }
    .coupon-table-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #FFE8D6;
        overflow: hidden;
    }
    .coupon-table {
        width: 100%;
        border-collapse: collapse;
    }
    .coupon-table th {
        background-color: #FFF9F3;
        color: #E06A20;
        font-weight: 600;
        padding: 14px 16px;
        font-size: 0.9rem;
        border-bottom: 2px solid #FFE8D6;
        text-align: left;
    }
    .coupon-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #FFF1E3;
        color: #4a4a4a;
        font-size: 0.9rem;
    }
    .coupon-table tbody tr:hover { background-color: #FFF9F3; }
    .coupon-code-badge {
        background: linear-gradient(135deg, #FF8C42, #FFB877);
        color: #fff;
        padding: 4px 12px;
        border-radius: 6px;
        font-weight: 700;
        font-family: monospace;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }
    .discount-text { font-weight: 700; color: #E06A20; }
    .status-active {
        background-color: #e6f4ea;
        color: #137333;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .status-inactive {
        background-color: #fce8e6;
        color: #c5221f;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    .action-buttons .btn {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .action-buttons .btn:hover { transform: translateY(-1px); }
    .btn-edit { background-color: #fff3cd; color: #856404; }
    .btn-edit:hover { background-color: #ffeeba; }
    .btn-delete { background-color: #f8d7da; color: #bd2130; }
    .btn-delete:hover { background-color: #f5c6cb; }
    .table-empty {
        padding: 50px !important;
        text-align: center;
        color: #a38974;
    }
    .usage-text { font-size: 0.85rem; color: #888; }
</style>

<div class="coupon-container">
    <div class="coupon-header">
        <h2>🎟️ จัดการคูปองส่วนลด</h2>
        <a href="{{ route('coupons.create') }}" class="btn-add-coupon">
            <i class="bi bi-plus-circle"></i> เพิ่มคูปองใหม่
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">🎉 {{ session('success') }}</div>
    @endif

    <div class="coupon-table-card">
        <div style="overflow-x: auto;">
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
                        <th style="text-align: center;">จัดการ</th>
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
                                    <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-edit">✏️ แก้ไข</a>
                                    <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('ยืนยันลบคูปองนี้?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete">🗑️ ลบ</button>
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
</div>
@endsection
