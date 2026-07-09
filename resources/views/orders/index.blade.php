@extends('layouts.app')

@section('title', 'หน้าแรก - เมนูแนะนำ')

@section('styles')
<style>
    /* ==========================================================
       ธีมสีส้ม-ขาว (Coffee Shop Orange & White Theme)
       ========================================================== */
    :root {
        --cs-orange: #FF8C42;
        --cs-orange-light: #FFB877;
        --cs-orange-dark: #E06A20;
        --cs-cream: #FFF9F3;
        --cs-text: #333333;
        --cs-gradient: linear-gradient(135deg, var(--cs-orange) 0%, var(--cs-orange-light) 100%);
    }

    /* ---------- Hero Section ---------- */
    .hero-section {
        position: relative;
        background:
            linear-gradient(135deg, rgba(224, 106, 32, 0.75), rgba(255, 140, 66, 0.55)),
            url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&q=80') center/cover;
        color: #fff;
        padding: 100px 20px;
        border-radius: 24px;
        text-align: center;
        margin-bottom: 50px;
        box-shadow: 0 20px 45px rgba(224, 106, 32, 0.25);
        overflow: hidden;
        animation: heroFadeIn 0.9s ease both;
    }
    .hero-section::before {
        /* วงกลมมัวๆ ตกแต่งพื้นหลังให้ดูมีมิติ */
        content: '';
        position: absolute;
        width: 260px;
        height: 260px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        top: -80px;
        right: -60px;
        filter: blur(10px);
    }
    .hero-section .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.4);
        padding: 6px 18px;
        border-radius: 30px;
        font-size: 0.85rem;
        letter-spacing: 1px;
        margin-bottom: 18px;
        backdrop-filter: blur(4px);
    }
    .hero-section h1 {
        font-weight: 800;
        font-size: 2.9rem;
        margin-bottom: 12px;
        text-shadow: 0 4px 14px rgba(0,0,0,0.25);
    }
    .hero-section p {
        font-size: 1.2rem;
        color: #fff5ec;
        margin-bottom: 30px;
        opacity: 0.95;
    }
    @keyframes heroFadeIn {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ---------- หัวข้อ Section ---------- */
    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 800;
        font-size: 1.5rem;
        border-left: 5px solid var(--cs-orange);
        padding-left: 15px;
        margin-bottom: 6px;
        color: var(--cs-text);
    }
    .section-subtitle {
        color: #9a8f86;
        padding-left: 20px;
        margin-bottom: 28px;
        font-size: 0.95rem;
    }

    /* ---------- Grid เมนูแนะนำ ---------- */
    .rec-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 26px;
        padding-bottom: 60px;
    }

    /* ---------- การ์ดเมนู ---------- */
    .rec-card {
        position: relative;
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(224, 106, 32, 0.08);
        transition: transform 0.35s cubic-bezier(.22,1,.36,1), box-shadow 0.35s ease;
        text-align: center;
        padding-bottom: 22px;
        border: 1px solid #FFE8D6;
        animation: cardIn 0.6s ease both;
    }
    .rec-card:hover {
        transform: translateY(-10px) scale(1.015);
        box-shadow: 0 18px 34px rgba(224, 106, 32, 0.2);
        border-color: var(--cs-orange-light);
    }
    @keyframes cardIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .rec-img-wrap {
        position: relative;
        overflow: hidden;
        height: 220px;
    }
    .rec-img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }
    .rec-card:hover .rec-img {
        transform: scale(1.08);
    }
    .rec-img-placeholder {
        width: 100%;
        height: 220px;
        background: linear-gradient(135deg, #FFF1E3, #FFE3CC);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: var(--cs-orange);
        transition: transform 0.5s ease;
    }
    .rec-card:hover .rec-img-placeholder {
        transform: scale(1.08);
    }

    /* ริบบิ้น "แนะนำ" มุมการ์ด */
    .rec-ribbon {
        position: absolute;
        top: 14px;
        left: -6px;
        background: var(--cs-gradient);
        color: #fff;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 5px 14px 5px 18px;
        border-radius: 0 20px 20px 0;
        box-shadow: 0 4px 10px rgba(224, 106, 32, 0.35);
        letter-spacing: 0.5px;
    }

    .rec-name {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 18px 12px 4px;
        color: var(--cs-text);
    }
    .rec-price {
        color: var(--cs-orange-dark);
        font-weight: 800;
        font-size: 1.25rem;
        margin-bottom: 16px;
    }
    .rec-price::before {
        content: '฿';
        margin-right: 2px;
        opacity: 0.85;
    }

    /* ---------- ปุ่ม ---------- */
    .btn-main {
        background: var(--cs-gradient);
        color: #fff;
        border: none;
        padding: 13px 32px;
        border-radius: 30px;
        font-weight: 700;
        text-decoration: none;
        display: inline-block;
        box-shadow: 0 8px 20px rgba(224, 106, 32, 0.35);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .btn-main:hover {
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 12px 26px rgba(224, 106, 32, 0.45);
        color: #fff;
    }
    .btn-outline {
        border: 2px solid var(--cs-orange);
        color: var(--cs-orange-dark);
        background: #fff;
        padding: 8px 26px;
        border-radius: 20px;
        font-weight: 700;
        text-decoration: none;
        display: inline-block;
        transition: all 0.25s ease;
    }
    .btn-outline:hover {
        background: var(--cs-gradient);
        color: #fff;
        border-color: transparent;
        transform: translateY(-2px);
    }

    /* ---------- Empty State ---------- */
    .rec-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 50px 20px;
        background: var(--cs-cream);
        border: 2px dashed #FFD3A8;
        border-radius: 18px;
    }
    .rec-empty span { font-size: 3rem; display: block; margin-bottom: 8px; }
    .rec-empty h4 { color: var(--cs-orange-dark); margin-top: 10px; }
    .rec-empty p { color: #9a8f86; }
</style>
@endsection

@section('content')
    {{-- แบนเนอร์หน้าแรก --}}
    <div class="hero-section">
        <span class="hero-badge">☕ คั่วสดใหม่ทุกวัน</span>
        <h1>ยินดีต้อนรับสู่ Coffee Shop</h1>
        <p>เริ่มต้นวันใหม่ด้วยกาแฟแก้วโปรดที่คุณสามารถปรับแต่งได้เอง</p>
        <a href="{{ route('orders.create') }}" class="btn-main" style="font-size: 1.15rem;">
            🛒 เริ่มสั่งเครื่องดื่มเลย
        </a>
    </div>


    {{-- เมนูแนะนำถูกส่งมาจาก OrderController แล้ว --}}

    <div class="container-fluid px-0">
        <h3 class="section-title">⭐ เมนูแนะนำประจำวัน</h3>
        <p class="section-subtitle">คัดสรรมาเป็นพิเศษสำหรับวันนี้</p>

        <div class="rec-grid">
            @forelse($recommendedProducts as $index => $product)
                <div class="rec-card" style="animation-delay: {{ $index * 0.08 }}s">
                    <span class="rec-ribbon">แนะนำ</span>

                    <div class="rec-img-wrap">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rec-img">
                        @else
                            <div class="rec-img-placeholder">☕</div>
                        @endif
                    </div>

                    <div class="rec-name">{{ $product->name }}</div>
                    <div class="rec-price">{{ number_format($product->price, 0) }}</div>

                    {{-- ปุ่มกดแล้ววิ่งไปหน้า POS --}}
                    <a href="{{ route('orders.create') }}" class="btn-outline">สั่งเลย</a>
                </div>
            @empty
                <div class="rec-empty">
                    <span>📝</span>
                    <h4>ยังไม่มีสินค้าในระบบ</h4>
                    <p>กรุณาเพิ่มสินค้าหลังบ้านเพื่อให้แสดงเป็นเมนูแนะนำ</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- เมนูขายดี (Best Selling Dashboard) --}}
    <div class="container-fluid px-0" style="margin-top: 40px; margin-bottom: 20px;">
        <h3 class="section-title"> เมนูขายดีที่สุด</h3>
        <p class="section-subtitle">เมนูฮิตยอดนิยมที่ลูกค้าสั่งมากที่สุด</p>

        @if($bestSellingProducts->count() > 0)
            <div style="max-width: 800px; margin: 0 auto; background: white; padding: 25px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #f0e6dd;">
                <canvas id="bestSellingChart"></canvas>
            </div>
            
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const ctx = document.getElementById('bestSellingChart').getContext('2d');
                    
                    const labels = {!! json_encode($bestSellingProducts->pluck('name')) !!};
                    const data = {!! json_encode($bestSellingProducts->pluck('total_sold')) !!};
                    
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'จำนวนแก้วที่ขายได้',
                                data: data,
                                backgroundColor: [
                                    'rgba(255, 140, 66, 0.7)',
                                    'rgba(111, 78, 55, 0.7)',
                                    'rgba(197, 155, 39, 0.7)',
                                    'rgba(140, 98, 57, 0.7)',
                                    'rgba(200, 160, 140, 0.7)'
                                ],
                                borderColor: [
                                    'rgba(255, 140, 66, 1)',
                                    'rgba(111, 78, 55, 1)',
                                    'rgba(197, 155, 39, 1)',
                                    'rgba(140, 98, 57, 1)',
                                    'rgba(200, 160, 140, 1)'
                                ],
                                borderWidth: 1,
                                borderRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: 'Top 5 เมนูขายดีที่สุด',
                                    font: {
                                        size: 16,
                                        family: "'Sarabun', sans-serif"
                                    },
                                    color: '#4a3423'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                        font: { family: "'Sarabun', sans-serif" }
                                    },
                                    grid: { color: '#f5eee6' }
                                },
                                x: {
                                    ticks: { font: { family: "'Sarabun', sans-serif" } },
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                });
            </script>
        @else
            <div class="rec-empty">
                <span>📊</span>
                <h4>ยังไม่มีข้อมูลยอดขาย</h4>
                <p>เมื่อมีออเดอร์เข้ามา กราฟสินค้าขายดีจะแสดงที่นี่</p>
            </div>
        @endif
    </div>

    {{-- ประวัติการสั่งซื้อ (Order History) --}}
    <div class="container-fluid px-0" style="margin-top: 50px; margin-bottom: 40px;">
        <h3 class="section-title">🕒 ประวัติการสั่งซื้อ</h3>
        <p class="section-subtitle">รายการออเดอร์ทั้งหมดที่เคยสั่งซื้อ</p>

        <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f0e6dd; overflow: hidden;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #FFF9F3; border-bottom: 2px solid #FFE8D6;">
                            <th style="padding: 14px 20px; text-align: left; color: #E06A20; font-weight: 600;">เลขออเดอร์</th>
                            <th style="padding: 14px 20px; text-align: left; color: #E06A20; font-weight: 600;">วันที่สั่งซื้อ</th>
                            <th style="padding: 14px 20px; text-align: left; color: #E06A20; font-weight: 600;">ลูกค้า</th>
                            <th style="padding: 14px 20px; text-align: left; color: #E06A20; font-weight: 600;">รายการ</th>
                            <th style="padding: 14px 20px; text-align: right; color: #E06A20; font-weight: 600;">ยอดสุทธิ</th>
                            <th style="padding: 14px 20px; text-align: center; color: #E06A20; font-weight: 600;">สถานะ</th>
                            <th style="padding: 14px 20px; text-align: center; color: #E06A20; font-weight: 600;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr style="border-bottom: 1px solid #FFF1E3; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#FFF9F3'" onmouseout="this.style.backgroundColor='transparent'">
                                <td style="padding: 14px 20px; font-weight: 700; color: #4a3423;">{{ $order->order_number }}</td>
                                <td style="padding: 14px 20px; color: #666;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td style="padding: 14px 20px; color: #4a3423;">{{ $order->customer_name ?: '-' }}</td>
                                <td style="padding: 14px 20px; color: #666;">
                                    {{ $order->items->count() }} รายการ 
                                    <small style="color: #a38974;">({{ $order->items->sum('quantity') }} แก้ว)</small>
                                </td>
                                <td style="padding: 14px 20px; text-align: right; font-weight: 700; color: #4a3423;">฿{{ number_format($order->total, 2) }}</td>
                                <td style="padding: 14px 20px; text-align: center;">
                                    <span style="display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; 
                                        @if($order->status == 'pending') background-color: #fff3cd; color: #856404;
                                        @elseif($order->status == 'paid') background-color: #d4edda; color: #155724;
                                        @elseif($order->status == 'cancelled') background-color: #f8d7da; color: #721c24;
                                        @endif
                                    ">
                                        {{ $order->status_thai }}
                                    </span>
                                </td>
                                <td style="padding: 14px 20px; text-align: center;">
                                    <a href="{{ route('orders.show', $order) }}" style="display: inline-block; background-color: #6f4e37; color: white; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#4a3423'" onmouseout="this.style.backgroundColor='#6f4e37'">
                                        🔍 ดูรายละเอียด
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 40px; text-align: center; color: #a38974;">
                                    <div style="font-size: 2rem; margin-bottom: 10px;">📋</div>
                                    <p>ยังไม่มีประวัติการสั่งซื้อ</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($orders->hasPages())
                <div style="padding: 15px 20px; border-top: 1px solid #FFE8D6; background-color: #fdfbf9;">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection