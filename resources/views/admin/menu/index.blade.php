@extends('layouts.admin')

@section('title', 'จัดการเมนูร้านกาแฟ')

@section('content')
<style>
    /* ===== STYLE SPECIFIC FOR MENU INDEX PAGE ===== */
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
</style>

<div class="menu-manager-header">
    <h2>⚙️ ระบบตั้งค่าเมนูร้านกาแฟ</h2>
    <a href="{{ route('admin.menu.create') }}" class="btn btn-add-menu">
        <i class="bi bi-plus-circle"></i> เพิ่มเมนูใหม่
    </a>
</div>

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
                        </td> {{-- 🛠️ อุดรอยรั่ว: แก้ไขแท็กปิดคอลัมน์ที่ตกหล่นให้ถูกต้องเรียบร้อย --}}
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