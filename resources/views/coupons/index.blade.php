@extends('layouts.admin')

@section('title', 'จัดการคูปอง')

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

    /* 🔔 แจ้งเตือนเด้งมุมขวาบนแบบสไลด์ (Toast Notification) */
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
    </main>

</div>
@endsection