@extends('layouts.app')

@section('title', 'ออเดอร์ ' . $order->order_number)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/order-detail.css') }}">
@endsection

@section('content')
    <div class="order-detail-header">
        <div>
            <h1>☕ ออเดอร์ {{ $order->order_number }}</h1>
            <div class="order-meta">
                สร้างเมื่อ {{ $order->created_at->format('d/m/Y H:i:s') }}
                &nbsp;·&nbsp;
                <span class="badge badge-{{ $order->status_color }}">{{ $order->status_thai }}</span>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('orders.index') }}" class="btn btn-outline">← กลับรายการ</a>
            <a href="{{ route('orders.receipt', $order) }}" target="_blank" class="btn btn-primary" style="background-color: #4a3423; color: white;">🖨️ พิมพ์ใบเสร็จ</a>
            <a href="{{ route('orders.create') }}" class="btn btn-primary">🛒 สร้างออเดอร์ใหม่</a>
        </div>
    </div>

    <div class="detail-grid">
        {{-- Items --}}
        <div class="detail-card">
            {{-- 🛠️ แก้ไข: ดึงผ่าน orderItems --}}
            <div class="card-title">🛒 รายการสินค้า ({{ $order->items->count() }} รายการ)</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>สินค้า</th>
                        <th>จำนวน</th>
                        <th>ราคา</th>
                        <th>รวม</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- 🛠️ แก้ไข: เปลี่ยนมาลูปผ่าน orderItems --}}
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td style="color: var(--coffee-400);">{{ $index + 1 }}</td>
                            <td>
                                <div class="item-name">{{ optional($item->product)->name }}</div>
                                <div class="item-options">
                                    <span class="item-option-tag">🍯 {{ $item->sweetness_level }}</span>
                                    @if($item->temperature)
                                        <span class="item-option-tag">
                                            {{ $item->temperature }}
                                        </span>
                                    @endif
                                </div>
                                @if($item->note)
                                    <div class="item-note">📝 {{ $item->note }}</div>
                                @endif
                            </td>
                            <td>x{{ $item->quantity }}</td>
                            <td>฿{{ number_format($item->price, 0) }}</td>
                            <td class="item-subtotal">฿{{ number_format($item->subtotal, 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Summary + Info --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            {{-- Summary --}}
            <div class="detail-card">
                <div class="card-title">💰 สรุปยอด</div>
                <div class="card-body">
                    <div class="summary-row">
                        <span>ยอดรวม</span>
                        {{-- 🛠️ แก้ไข: กรณีไม่มีฟิลด์ย่อย ให้ใช้ราคาตั้งต้นมาคิดราคา --}}
                        <span>฿{{ number_format($order->items->sum('subtotal'), 2) }}</span>
                    </div>
                    @if(isset($order->discount_amount) && $order->discount_amount > 0)
                        <div class="summary-row discount">
                            <span>ส่วนลด{{ $order->coupon ? ' (' . $order->coupon->code . ')' : '' }}</span>
                            <span>-฿{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="summary-row grand-total">
                        <span>ยอดสุทธิ</span>
                        {{-- 🛠️ แก้ไข: เปลี่ยนเป็น total_price ตามตารางฐานข้อมูล --}}
                        <span>฿{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                @if($order->status === 'pending')
                    <div class="status-change-section">
                        <form method="POST" action="{{ route('orders.update-status', $order) }}" style="flex:1">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="paid">
                            <button type="submit" class="btn btn-success" style="width:100%">✅ ชำระเงินแล้ว</button>
                        </form>
                        <form method="POST" action="{{ route('orders.update-status', $order) }}" style="flex:1">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-danger" style="width:100%" onclick="return confirm('ยืนยันยกเลิก?')">❌ ยกเลิก</button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Customer & Order Info --}}
            <div class="detail-card">
                <div class="card-title">📋 ข้อมูลออเดอร์</div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">เลขออเดอร์</span>
                        <span class="info-value">{{ $order->order_number }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">สถานะ</span>
                        <span class="badge badge-{{ $order->status_color }}">{{ $order->status_thai }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">วิธีชำระเงิน</span>
                        <span class="info-value">{{ $order->payment_method ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">ชื่อลูกค้า</span>
                        <span class="info-value">{{ $order->customer_name ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">เบอร์โทร</span>
                        <span class="info-value">{{ $order->customer_phone ?? '-' }}</span>
                    </div>
                    @if($order->coupon)
                        <div class="info-row">
                            <span class="info-label">คูปอง</span>
                            <span class="info-value">{{ $order->coupon->code }}</span>
                        </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">เวลาสร้าง</span>
                        <span class="info-value">{{ $order->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">อัพเดทล่าสุด</span>
                        <span class="info-value">{{ $order->updated_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection