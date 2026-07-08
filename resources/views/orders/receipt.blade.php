<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบเสร็จรับเงิน - ออเดอร์ {{ $order->order_number }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Sarabun', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            justify-content: center;
        }
        .receipt-container {
            background-color: white;
            width: 80mm; /* ขนาดกระดาษสลิปมาตรฐาน (80mm) */
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .text-center { text-align: center; }
        .receipt-header {
            margin-bottom: 15px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
        }
        .shop-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 0 5px 0;
            color: #4a3423;
        }
        .receipt-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        .order-meta {
            font-size: 0.85rem;
            margin-bottom: 15px;
        }
        .order-meta p { margin: 3px 0; }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        .items-table th {
            border-bottom: 1px dashed #ccc;
            padding-bottom: 5px;
            text-align: left;
        }
        .items-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .item-qty { width: 15%; text-align: left; }
        .item-name { width: 55%; }
        .item-price { width: 30%; text-align: right; }
        
        .item-options {
            font-size: 0.75rem;
            color: #666;
            margin-top: 2px;
        }
        
        .summary-section {
            border-top: 1px dashed #ccc;
            padding-top: 10px;
            margin-bottom: 20px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        .summary-row.total {
            font-weight: bold;
            font-size: 1.1rem;
            margin-top: 5px;
            border-top: 1px dashed #ccc;
            padding-top: 5px;
        }
        
        .receipt-footer {
            text-align: center;
            font-size: 0.85rem;
            border-top: 1px dashed #ccc;
            padding-top: 15px;
            color: #666;
        }
        
        /* ซ่อนปุ่มเวลาพิมพ์ */
        @media print {
            body { background-color: white; padding: 0; display: block; }
            .receipt-container { width: 100%; box-shadow: none; padding: 0; }
            .no-print { display: none !important; }
        }
        
        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4a3423;
            color: white;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-family: inherit;
            font-size: 1rem;
            cursor: pointer;
            margin-bottom: 20px;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    
    <div>
        <button onclick="window.print()" class="no-print btn-print">🖨️ พิมพ์ใบเสร็จ</button>
        <button onclick="window.close()" class="no-print btn-print" style="background-color: #6c757d; margin-top: 5px;">ปิดหน้าต่าง</button>
        
        <div class="receipt-container">
            <div class="receipt-header text-center">
                <h1 class="shop-name">☕ Coffee Shop</h1>
                <h2 class="receipt-title">ใบเสร็จรับเงิน</h2>
            </div>
            
            <div class="order-meta">
                <p><strong>เลขออเดอร์:</strong> {{ $order->order_number }}</p>
                <p><strong>วันที่:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                @if($order->customer_name)
                <p><strong>ลูกค้า:</strong> {{ $order->customer_name }}</p>
                @endif
            </div>
            
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="item-qty">จำนวน</th>
                        <th class="item-name">รายการ</th>
                        <th class="item-price">รวม</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td class="item-qty">{{ $item->quantity }}</td>
                        <td class="item-name">
                            {{ optional($item->product)->name }}
                            <div class="item-options">
                                {{ $item->sweetness_level }} 
                                {{ $item->temperature ? ', ' . $item->temperature : '' }}
                            </div>
                            @if($item->note)
                            <div class="item-options" style="font-style: italic;">({{ $item->note }})</div>
                            @endif
                        </td>
                        <td class="item-price">{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="summary-section">
                <div class="summary-row">
                    <span>รวมเป็นเงิน</span>
                    <span>{{ number_format($order->items->sum('subtotal'), 2) }}</span>
                </div>
                
                @if(isset($order->discount_amount) && $order->discount_amount > 0)
                <div class="summary-row">
                    <span>ส่วนลด {{ $order->coupon ? '(' . $order->coupon->code . ')' : '' }}</span>
                    <span>-{{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @endif
                
                <div class="summary-row total">
                    <span>ยอดสุทธิ</span>
                    <span>{{ number_format($order->total, 2) }} บาท</span>
                </div>
                
                @if($order->payment_method)
                <div class="summary-row" style="margin-top: 10px; font-size: 0.85rem;">
                    <span>ชำระโดย</span>
                    <span>{{ $order->payment_method }}</span>
                </div>
                @endif
            </div>
            
            <div class="receipt-footer">
                <p style="margin-bottom: 5px;">ขอบคุณที่ใช้บริการ ☕</p>
                <p style="font-size: 0.75rem; margin-top: 0;">Powered by POS System</p>
            </div>
        </div>
    </div>
    
    <script>
        // พิมพ์อัตโนมัติเมื่อเปิดหน้า (เฉพาะถ้าไม่ได้มีพารามิเตอร์ป้องกัน)
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (!urlParams.has('noprint')) {
                // setTimeout(function() { window.print(); }, 500);
            }
        };
    </script>
</body>
</html>
