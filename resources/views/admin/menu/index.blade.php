@extends('layouts.admin')

@section('title', 'จัดการเมนูร้านกาแฟ')

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

    .menu-manager-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e8dfd8;
    }
    
    .menu-manager-header h2 {
        color: #4a3423;
        font-size: 1.6rem;
        margin: 0;
        font-weight: 600;
    }
    
    .btn-add-menu {
        background-color: #6f4e37;
        color: #ffffff !important;
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
    
    .btn-add-menu:hover {
        background-color: #523928;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(111, 78, 55, 0.25);
    }
    
    /* 🔔 🛠️ จุดแก้ไขเพิ่มเติม: ย้ายกล่องข้อความสำเร็จลอยไปอยู่มุมขวาบนแบบอัตโนมัติ */
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
        
        /* ใส่เอฟเฟกต์อนิเมชัน: สไลด์เข้าขวา 0.4 วินาที และจางหายถอยหลังตอนวิที่ 3.5 */
        animation: slideInRight 0.4s ease-out forwards, fadeOut 0.5s ease-in 3.5s forwards;
    }
    
    /* สไลด์วิ่งเข้ามาจากนอกขอบจอขวา */
    @keyframes slideInRight {
        from { transform: translateX(120%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    /* สเกลยุบตัวและค่อยๆ จางหายไป */
    @keyframes fadeOut {
        from { opacity: 1; transform: scale(1); }
        to { opacity: 0; transform: scale(0.9); visibility: hidden; }
    }
    
    .table-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        border: 1px solid #f0e6dd;
        overflow: hidden;
    }
    
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }
    
    .menu-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    
    .menu-table th {
        background-color: #fcf9f6;
        color: #6f4e37;
        font-weight: 600;
        padding: 16px;
        font-size: 0.95rem;
        border-bottom: 2px solid #e8dfd8;
    }
    
    .menu-table td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f5eee6;
        color: #4a4a4a;
        font-size: 0.95rem;
    }
    
    .menu-table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .menu-table tbody tr:hover {
        background-color: #faf6f0;
    }
    
    .menu-img-preview {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e8dfd8;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        display: block;
    }
    
    .menu-no-img {
        width: 60px;
        height: 60px;
        background: #f7f2ed;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #a38974;
        font-size: 0.8rem;
        font-weight: 500;
        border: 1px dashed #dfcfbe;
    }
    
    .menu-link-name {
        color: #4a3423;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.05rem;
        transition: color 0.2s;
    }
    
    .menu-link-name:hover {
        color: #c59b27;
    }
    
    .menu-desc-short {
        margin: 4px 0 0 0;
        font-size: 0.85rem;
        color: #8c8c8c;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .badge-category {
        background-color: #f3ece6;
        color: #8c6239;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .menu-price-text {
        font-weight: bold;
        color: #6f4e37;
        font-size: 1.05rem;
    }
    
    .status-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-badge.available {
        background-color: #e6f4ea;
        color: #137333;
    }
    
    .status-badge.unavailable {
        background-color: #fce8e6;
        color: #c5221f;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
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

    .action-buttons .btn i {
        font-size: 1rem; 
    }
    
    .btn-info { background-color: #e4f2f5; color: #117a8b; }
    .btn-info:hover { background-color: #bee5eb; }

    .btn-warning { background-color: #fff3cd; color: #856404; }
    .btn-warning:hover { background-color: #ffeeba; }

    .btn-danger { background-color: #f8d7da; color: #bd2130; }
    .btn-danger:hover { background-color: #f5c6cb; }
    
    .table-empty-state {
        padding: 50px !important;
        text-align: center;
        color: #a38974;
        font-size: 1rem;
    }

    @media (max-width: 992px) {
        .admin-sidebar { width: 70px; }
        .sidebar-brand span, .sidebar-user-profile .user-info-text, .sidebar-menu-item a span, .btn-sidebar-logout span {
            display: none;
        }
        .admin-main-content { margin-left: 70px; padding: 20px; }
        .sidebar-brand, .sidebar-menu-list a, .sidebar-user-profile { justify-content: center; justify-items: center; }
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
        <div class="menu-manager-header">
            <h2>⚙️ ระบบตั้งค่าเมนูร้านกาแฟ</h2>
            <a href="{{ route('admin.menu.create') }}" class="btn btn-add-menu">
                <i class="bi bi-plus-circle"></i> เพิ่มเมนูใหม่
            </a>
        </div>

        {{-- 🛠️ เปลี่ยนโครงสร้างตัวครอบเพื่อรองรับคุณสมบัติเด้งมุมขวาบนไร้การดันตาราง --}}
        @if(session('success'))
            <div class="alert alert-success">
                <span>🎉 {{ session('success') }}</span>
            </div>
        @endif

        <div class="table-card">
            <div class="table-responsive">
                <table class="menu-table">
                    <thead>
                        <tr>
                            <th style="width: 80px; text-align: center;">รูปภาพ</th>
                            <th>ชื่อเมนู / รายละเอียด</th>
                            <th>หมวดหมู่</th>
                            <th>ราคา</th>
                            <th>สถานะ</th>
                            <th style="width: 280px; text-align: center;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr id="row-{{ $product->id }}">
                                <td style="text-align: center;">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="menu-img-preview" alt="{{ $product->name }}">
                                    @else
                                        <div class="menu-no-img">No Img</div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.menu.show', $product->id) }}" class="menu-link-name">
                                        {{ $product->name }}
                                    </a>
                                    <p class="menu-desc-short">{{ $product->description ?? 'ไม่มีคำอธิบายเมนูนี้' }}</p>
                                </td>
                                <td>
                                    <span class="badge-category">{{ $product->category }}</span>
                                
                                <td>
                                    <span class="menu-price-text">{{ number_format($product->price, 2) }} ฿</span>
                                </td>
                                <td>
                                    @if($product->status == 1)
                                        <span class="status-badge available">พร้อมขาย</span>
                                    @else
                                        <span class="status-badge unavailable">หมดชั่วคราว</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.menu.show', $product->id) }}" class="btn btn-info" title="ดูรายละเอียด">
                                            <i class="bi bi-search"></i> ดูข้อมูล
                                        </a>
                                        <a href="{{ route('admin.menu.edit', $product->id) }}" class="btn btn-warning" title="แก้ไข">
                                            <i class="bi bi-pencil-square"></i> แก้ไข
                                        </a>
                                        <button type="button" class="btn btn-danger" onclick="deleteMenu({{ $product->id }})" title="ลบ">
                                            <i class="bi bi-trash3"></i> ลบ
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="table-empty-state">
                                    ☕ ยังไม่มีข้อมูลเมนูในร้านกาแฟของคุณ กดปุ่ม "เพิ่มเมนูใหม่" ด้านบนเพื่อเริ่มเพิ่มสินค้าได้เลย
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</div>

{{-- ส่วนเสริมสคริปต์ลบรายการแบบไร้รอยต่อ --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteMenu(id) {
    Swal.fire({
        title: 'ยืนยันการลบเมนู?',
        text: "รายการนี้จะถูกถอดออกจากระบบและหน้า POS ทันที!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6f4e37', 
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ลบเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/admin/menu/' + id,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(response) {
                    if(response.success) {
                        Swal.fire({ 
                            title: 'ลบสำเร็จ!', 
                            text: response.message, 
                            icon: 'success', 
                            timer: 1500, 
                            showConfirmButton: false 
                        });
                        $('#row-' + id).fadeOut(500, function() { $(this).remove(); });
                    }
                }
            });
        }
    })
}
</script>
@endsection