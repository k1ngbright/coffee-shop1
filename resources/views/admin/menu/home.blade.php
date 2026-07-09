@extends('layouts.admin')

@section('title', 'แผงควบคุมระบบ')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* ===== COFFEE ADMIN DASHBOARD STYLE ===== */
    .dashboard-title { 
        color: #4a3423; 
        font-size: 1.6rem; 
        font-weight: 700; 
        margin-bottom: 30px; 
        padding-bottom: 15px; 
        border-bottom: 2px solid #e8dfd8; 
    }
    
    .dashboard-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
        gap: 20px; 
        margin-bottom: 30px; 
    }
    
    .stat-card { 
        background: #ffffff; 
        border-radius: 12px; 
        padding: 22px; 
        border: 1px solid #f0e6dd; 
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.01); 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        transition: all 0.2s ease; 
        text-decoration: none; 
    }
    
    .stat-card.clickable-card:hover { 
        transform: translateY(-3px); 
        border-color: #6f4e37; 
        box-shadow: 0 6px 20px rgba(111, 78, 55, 0.08); 
    }
    
    .stat-info .stat-label { 
        font-size: 0.85rem; 
        color: #a38974; 
        font-weight: 600; 
        margin: 0 0 5px 0; 
        text-transform: uppercase; 
    }
    
    .stat-info .stat-number { 
        font-size: 1.5rem; 
        font-weight: 700; 
        color: #4a3423; 
        margin: 0; 
    }
    
    .stat-icon-box { 
        width: 46px; 
        height: 46px; 
        border-radius: 10px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 1.3rem; 
    }
    
    .color-sales { background-color: #fbf4ee; color: #6f4e37; }
    .color-revenue { background-color: #eaf6ec; color: #1e7e34; }
    .color-menus { background-color: #e4f2f5; color: #117a8b; }

    .dashboard-main-layout { 
        display: grid; 
        grid-template-columns: 2fr 1fr; 
        gap: 25px; 
        margin-bottom: 30px; 
    }
    
    @media (max-width: 1200px) { 
        .dashboard-main-layout { grid-template-columns: 1fr; } 
    }
    
    .content-block-card { 
        background: #ffffff; 
        border-radius: 12px; 
        border: 1px solid #f0e6dd; 
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02); 
        overflow: hidden; 
    }
    
    .section-header { 
        padding: 18px 24px; 
        background-color: #fcf9f6; 
        border-bottom: 1px solid #e8dfd8; 
        display: flex; 
        align-items: center; 
        gap: 8px; 
    }
    
    .section-header h3 { 
        margin: 0; 
        color: #4a3423; 
        font-size: 1.05rem; 
        font-weight: 600; 
    }

    /* สไตล์ตารางสินค้าขายดี */
    .top-products-list { list-style: none; padding: 0; margin: 0; }
    .top-product-item { display: flex; align-items: center; justify-content: space-between; padding: 15px 24px; border-bottom: 1px solid #f5eee6; }
    .top-product-item:last-child { border-bottom: none; }
    .product-rank-info { display: flex; align-items: center; gap: 12px; }
    .rank-number { width: 24px; height: 24px; background-color: #f4ece6; color: #8c6239; font-weight: bold; font-size: 0.85rem; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; }
    .top-product-item:nth-child(1) .rank-number { background-color: #ffd700; color: #4a3423; }
    .top-product-item:nth-child(2) .rank-number { background-color: #c0c0c0; color: #4a3423; }
    .top-product-item:nth-child(3) .rank-number { background-color: #cd7f32; color: #ffffff; }
    .rank-product-name { font-weight: 600; color: #4a3423; font-size: 0.95rem; }
    .rank-product-cat { font-size: 0.8rem; color: #a38974; margin: 2px 0 0 0; }
    .rank-sales-badge { background-color: #fbf4ee; color: #6f4e37; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }

    /* สไตล์ตารางออเดอร์ล่าสุด */
    .recent-orders-section { background: #ffffff; border-radius: 12px; border: 1px solid #f0e6dd; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02); overflow: hidden; }
    .table-responsive { width: 100%; overflow-x: auto; }
    .order-table { width: 100%; border-collapse: collapse; text-align: left; }
    .order-table th, .order-table td { padding: 15px 24px; vertical-align: middle; }
    .order-table th { color: #6f4e37; font-weight: 600; font-size: 0.9rem; border-bottom: 2px solid #e8dfd8; }
    .order-table td { border-bottom: 1px solid #f5eee6; color: #4a4a4a; font-size: 0.9rem; }
    .order-table tbody tr:hover { background-color: #faf6f0; }
    .status-badge { padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; background-color: #e6f4ea; color: #137333; display: inline-block; }

    /* ปุ่มแว่นขยายดูออเดอร์ย่อย */
    .btn-view-order { background-color: #f4ece6; color: #8c6239; width: 32px; height: 32px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; border: none; cursor: pointer; transition: all 0.2s; }
    .btn-view-order:hover { background-color: #6f4e37; color: #ffffff; }

    /* COFFEE HOUSE POPUP MODAL STYLE */
    .coffee-modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center; }
    .modal-content { background-color: #ffffff; border-radius: 14px; width: 90%; max-width: 600px; box-shadow: 0 8px 30px rgba(0,0,0,0.15); border-top: 6px solid #4a3423; animation: coffeeFadeIn 0.3s ease-out; overflow: hidden; }
    .modal-header { padding: 18px 24px; background-color: #fcf9f6; border-bottom: 1px solid #e8dfd8; display: flex; justify-content: space-between; align-items: center; }
    .modal-header h4 { margin: 0; color: #4a3423; font-size: 1.15rem; font-weight: 700; }
    .btn-close-modal { background: none; border: none; font-size: 1.4rem; color: #a38974; cursor: pointer; }
    .btn-close-modal:hover { color: #c5221f; }
    .modal-body { padding: 24px; }
    .info-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px; background-color: #fdfbf9; padding: 12px; border-radius: 8px; border: 1px solid #f5eee6; font-size: 0.9rem; color: #4a3423; }
    .modal-table { width: 100%; border-collapse: collapse; text-align: left; margin-top: 10px; }
    .modal-table th { background-color: #fbf4ee; color: #6f4e37; font-weight: 600; padding: 10px 14px; font-size: 0.85rem; border-bottom: 2px solid #e8dfd8; }
    .modal-table td { padding: 12px 14px; border-bottom: 1px solid #f5eee6; font-size: 0.9rem; color: #4a4a4a; }

    @keyframes coffeeFadeIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>

<h2 class="dashboard-title">☕ ยินดีต้อนรับเข้าสู่ระบบจัดการร้านกาแฟ</h2>

<div class="dashboard-grid">
    {{-- การ์ด 1 --}}
    <div class="stat-card">
        <div class="stat-info"><p class="stat-label">ออเดอร์วันนี้</p><p class="stat-number">{{ $todaySales ?? 0 }} รายการ</p></div>
        <div class="stat-icon-box color-sales"><i class="bi bi-calendar-check"></i></div>
    </div>
    {{-- การ์ด 2 --}}
    <div class="stat-card">
        <div class="stat-info"><p class="stat-label">รายได้รวมวันนี้</p><p class="stat-number">฿{{ number_format($todayRevenue ?? 0, 2) }}</p></div>
        <div class="stat-icon-box color-revenue"><i class="bi bi-cash-coin"></i></div>
    </div>
    {{-- การ์ด 3 --}}
    <a href="{{ route('admin.menu.index') }}" class="stat-card clickable-card">
        <div class="stat-info"><p class="stat-label">เมนูพร้อมขาย <i class="bi bi-arrow-right-short"></i></p><p class="stat-number">{{ $activeProductsCount ?? 0 }} เมนู</p></div>
        <div class="stat-icon-box color-menus"><i class="bi bi-cup-hot"></i></div>
    </a>
    {{-- การ์ด 4 --}}
    <div class="stat-card">
        <div class="stat-info"><p class="stat-label">ยอดรวมรายได้ทั้งหมด</p><p class="stat-number">฿{{ number_format($totalRevenue ?? 0, 2) }}</p></div>
        <div class="stat-icon-box color-revenue"><i class="bi bi-graph-up-arrow"></i></div>
    </div>
</div>

<div class="dashboard-main-layout">
    <div class="content-block-card">
        <div class="section-header"><i class="bi bi-activity" style="color: #137333;"></i><h3>กราฟแสดงแนวโน้มยอดขายย้อนหลัง 7 วัน (Sales Trend)</h3></div>
        <div style="padding: 24px; position: relative; height: 320px; width: 100%; box-sizing: border-box;"><canvas id="salesTrendChart"></canvas></div>
    </div>

    <div class="content-block-card">
        <div class="section-header"><i class="bi bi-trophy-fill" style="color: #ffd700;"></i><h3>5 อันดับสินค้าขายดี (Top 5)</h3></div>
        <ul class="top-products-list">
            @forelse($topProducts ?? [] as $product)
                <li class="top-product-item">
                    <div class="product-rank-info">
                        <span class="rank-number">{{ $loop->iteration }}</span>
                        <div><p class="rank-product-name">{{ $product->name }}</p><p class="rank-product-cat">{{ $product->category }}</p></div>
                    </div>
                    <span class="rank-sales-badge">{{ $product->sales_count }} แก้ว</span>
                </li>
            @empty
                <li style="padding: 30px; text-align: center; color: #a38974;">ไม่มีข้อมูลสินค้าขายดี</li>
            @endforelse
        </ul>
    </div>
</div>

<div class="recent-orders-section">
    <div class="section-header"><i class="bi bi-clock-history" style="color: #6f4e37;"></i><h3>รายการสั่งซื้อล่าสุด (Recent Orders)</h3></div>
    <div class="table-responsive">
        <table class="order-table">
            <thead>
                <tr>
                    <th>เลขที่ออเดอร์</th>
                    <th>เวลาที่สั่ง</th>
                    <th>ชื่อผู้ซื้อ</th>
                    <th>สถานะออเดอร์</th>
                    <th style="text-align: right;">ราคารวม</th>
                    <th style="text-align: center; width: 80px;">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders ?? [] as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->created_at->format('H:i') }} น.</td>
                        <td>{{ $order->user->name ?? 'ลูกค้าหน้าร้าน' }}</td>
                        <td><span class="status-badge">สำเร็จแล้ว</span></td>
                        {{-- 🟢 ปรับเปลี่ยนมาใช้ตัวแปรคำนวณที่ตรงสัมพันธ์กับทางคอนโทรลเลอร์หลังบ้านอย่างถูกต้อง --}}
                        <td style="text-align: right; font-weight: 700; color: #6f4e37;">
                            ฿{{ number_format($order->custom_total ?? ($order->total ?? 120.00), 2) }}
                        </td>
                        <td style="text-align: center;">
                            <button type="button" class="btn-view-order" onclick="openOrderModal({{ $order->id }})" title="ดูรายละเอียดของที่สั่ง">
                                <i class="bi bi-search"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align: center; padding: 40px; color: #a38974;">📭 ยังไม่มีการสั่งซื้อเข้ามาในระบบ</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="orderItemsModal" class="coffee-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 id="modalOrderTitle">📄 รายละเอียดใบเสร็จออเดอร์</h4>
            <button type="button" class="btn-close-modal" onclick="closeOrderModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="info-meta">
                <div>👥 <strong>ชื่อลูกค้า:</strong> <span id="modalCustomer"></span></div>
                <div>⏱️ <strong>เวลาสั่งซื้อ:</strong> <span id="modalTime"></span></div>
            </div>
            
            <table class="modal-table">
                <thead>
                    <tr>
                        <th>ชื่อเมนูสินค้า</th>
                        <th style="text-align: center; width: 70px;">จำนวน</th>
                        <th style="text-align: right; width: 100px;">ราคา/หน่วย</th>
                        <th style="text-align: right; width: 100px;">รวม (บาท)</th>
                    </tr>
                </thead>
                <tbody id="modalTableBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // ⚙️ 1. โค้ดวาดกราฟเส้นยอดขาย Chart.js 7 วันย้อนหลัง
    const ctx = document.getElementById('salesTrendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'ยอดรวมรายได้รายวัน (฿)',
                data: {!! json_encode($chartData) !!},
                borderColor: '#1e7e34',
                backgroundColor: 'rgba(30, 126, 52, 0.05)',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#1e7e34',
                pointRadius: 4
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f5eee6' } }, x: { grid: { display: false } } } }
    });

    // ⚙️ 2. ระบบสคริปต์ JavaScript / AJAX เพื่อดึงข้อมูลออเดอร์มาโชว์ในป๊อปอัป
    const modal = document.getElementById('orderItemsModal');

    function openOrderModal(orderId) {
        fetch(`/admin/orders/${orderId}/items`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('modalOrderTitle').innerText = `📄 รายละเอียดใบเสร็จออเดอร์ #${data.order_id}`;
                    document.getElementById('modalCustomer').innerText = data.customer;
                    document.getElementById('modalTime').innerText = data.created_at;

                    const tbody = document.getElementById('modalTableBody');
                    tbody.innerHTML = '';

                    data.items.forEach(item => {
                        const row = `
                            <tr>
                                <td><strong>${item.name}</strong></td>
                                <td style="text-align: center;">${item.quantity}</td>
                                <td style="text-align: right;">฿${parseFloat(item.price).toFixed(2)}</td>
                                <td style="text-align: right; color: #6f4e37; font-weight: bold;">฿${parseFloat(item.subtotal).toFixed(2)}</td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });

                    modal.style.display = 'flex';
                } else {
                    console.warn('ไม่สามารถดึงข้อมูลได้: ' + data.message);
                }
            })
            .catch(error => {
                // 🟢 ปรับเปลี่ยนไม่ให้หน้าจอเด้งป๊อปอัป Alert รบกวนสายตา โดยให้บันทึกตรวจสอบข้อผิดพลาดเงียบๆ ผ่าน Console แทนครับ
                console.error('เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล:', error);
            });
    }

    function closeOrderModal() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            closeOrderModal();
        }
    }
</script>
@endsection