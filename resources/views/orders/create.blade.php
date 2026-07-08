@extends('layouts.app')

@section('title', 'สร้างออเดอร์')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/pos.css') }}">
@endsection

@section('content')
<div class="pos-container">
    {{-- ===== MENU SECTION ===== --}}
    <div class="menu-section">
        <div class="menu-header">
            <h1>📋 เมนูสินค้า</h1>
            <div class="category-tabs">
                <button class="category-tab active" onclick="filterCategory('all', this)">🍽️ ทั้งหมด</button>
                @foreach($categories as $cat)
                    {{-- 🛠️ แก้ไข: เปลี่ยนจาก $cat->name เป็น $cat ตรงๆ เนื่องจากดึงผ่าน Array ข้อความของตาราง menus แล้ว --}}
                    <button class="category-tab" onclick="filterCategory('{{ $cat }}', this)">
                        @if($cat === 'กาแฟ') ☕
                        @elseif($cat === 'ชา') 🍵
                        @elseif($cat === 'เครื่องดื่มอื่นๆ') 🧋
                        @elseif($cat === 'เบเกอรี่') 🍞
                        @else 🍽️
                        @endif
                        {{ $cat }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="menu-grid-wrapper">
            <div class="menu-grid" id="menuGrid">
                @foreach($products as $product)
                    {{-- 🛠️ ตรวจสอบสถานะ: แสดงเฉพาะสินค้าที่พร้อมขาย (is_available == 1) --}}
                    @if($product->status)
                        {{-- 🛠️ แก้ไข: เปลี่ยนจาก $product->category->name เป็น $product->category สตริงตรงๆ ตามสเปกตาราง menus --}}
                        <div class="product-card"
                             data-category="{{ $product->category }}"
                             data-id="{{ $product->id }}"
                             data-name="{{ $product->name }}"
                             data-price="{{ $product->price }}"
                             onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, this)">
                            
                            <span class="product-category-badge">{{ $product->category }}</span>
                            
                            {{-- 🛠️ แสดงรูปภาพจริงที่เพิ่งอัปโหลดผ่านระบบจัดการเมนู หากเมนูไหนไม่มีรูปจะดึง Emoji ขึ้นมาแทน --}}
                            <div class="product-image-box" style="width: 100%; height: 120px; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 8px; background: #f7f2ed;">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span class="product-emoji" style="font-size: 3rem;">
                                        @if($product->category === 'กาแฟ') ☕
                                        @elseif($product->category === 'ชา') 🍵
                                        @elseif($product->category === 'เครื่องดื่มอื่นๆ') 🧋
                                        @elseif($product->category === 'เบเกอรี่') 🍞
                                        @else 🍽️
                                        @endif
                                    </span>
                                @endif
                            </div>

                            <div class="product-name" style="margin-top: 10px; font-weight: 600;">{{ $product->name }}</div>
                            <div class="product-price">฿{{ number_format($product->price, 0) }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===== CART SECTION ===== --}}
    <div class="cart-section">
        <div class="cart-header">
            <h2>🛒 ตะกร้า <span class="cart-count" id="cartCount">0</span></h2>
            <button class="cart-clear-btn" onclick="clearCart()" id="clearCartBtn" style="display: none;">🗑️ ล้าง</button>
        </div>

        <div class="cart-items" id="cartItems">
            <div class="cart-empty" id="cartEmpty" style="margin-top: 50px; text-align: center;">
                <p>ยังไม่มีสินค้าในตะกร้า<br><span style="font-size: 0.8rem; color: var(--coffee-300)">คลิกที่เมนูเพื่อเพิ่มสินค้า</span></p>
            </div>
        </div>

        <div class="cart-footer" id="cartFooter" style="display: none;">
            {{-- Customer Info --}}
            <div class="customer-section">
                <button class="customer-toggle" onclick="toggleCustomer()" type="button">
                    👤 ข้อมูลลูกค้า (ไม่บังคับ) <span id="customerArrow">▸</span>
                </button>
                <div class="customer-fields" id="customerFields" {!! auth()->check() ? 'style="display: block;"' : '' !!}>
                    <input type="text" class="input-field" id="customerName" placeholder="ชื่อลูกค้า" value="{{ auth()->check() ? auth()->user()->name : '' }}">
                    <input type="text" class="input-field" id="customerPhone" placeholder="เบอร์โทร">
                </div>
            </div>

            {{-- Coupon --}}
            <div class="coupon-section">
                <div class="coupon-input-group">
                    <input type="text" class="coupon-input" id="couponCode" placeholder="🎟️ ใส่รหัสคูปอง">
                    <button class="btn btn-outline btn-sm" onclick="applyCoupon()" type="button" id="couponBtn">ใช้คูปอง</button>
                </div>
                <div class="coupon-message" id="couponMessage"></div>
            </div>

            {{-- Totals --}}
            <div class="cart-totals">
                <div class="total-row">
                    <span>ยอดรวม</span>
                    <span id="subtotalDisplay">฿0</span>
                </div>
                <div class="total-row discount" id="discountRow" style="display: none;">
                    <span>ส่วนลด</span>
                    <span id="discountDisplay">-฿0</span>
                </div>
                <div class="total-row grand-total">
                    <span>ยอดสุทธิ</span>
                    <span id="totalDisplay">฿0</span>
                </div>
            </div>

            {{-- Payment --}}
            <div class="payment-section">
                <div class="payment-label">💳 วิธีชำระเงิน</div>
                <div class="payment-options">
                    <button class="payment-option selected" onclick="selectPayment('เงินสด', this)" type="button">💵 เงินสด</button>
                    <button class="payment-option" onclick="selectPayment('โอนเงิน', this)" type="button">📱 โอนเงิน</button>
                    <button class="payment-option" onclick="selectPayment('บัตรเครดิต', this)" type="button">💳 บัตร</button>
                </div>
            </div>

            {{-- Submit --}}
            <form id="orderForm" method="POST" action="{{ route('orders.store') }}">
                @csrf
                <input type="hidden" name="customer_name" id="formCustomerName">
                <input type="hidden" name="customer_phone" id="formCustomerPhone">
                <input type="hidden" name="coupon_code" id="formCouponCode">
                <input type="hidden" name="payment_method" id="formPaymentMethod" value="เงินสด">
                <div id="formItems"></div>
                <button type="submit" class="submit-order-btn" id="submitBtn" disabled>
                    ☕ ยืนยันออเดอร์
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ===== QR CODE MODAL ===== --}}
<div id="qrModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <h2 style="margin-bottom: 15px; color: #155724; display: flex; align-items: center; justify-content: center; gap: 10px;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c5/PromptPay-logo.png" alt="PromptPay" height="30">
            สแกนเพื่อโอนเงิน
        </h2>
        <div style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; text-align: center; border: 2px solid #28a745;">
            {{-- รูปแบบ QR Code (สามารถใช้ library สร้างจากเบอร์โทรหรือพร้อมเพย์ได้ ในที่นี้ใช้รูปจำลอง) --}}
            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QR Code" style="width: 200px; height: 200px; margin-bottom: 15px;">
            
            <div style="font-size: 1.2rem; font-weight: bold; color: #4a3423;">ยอดโอนสุทธิ: <span id="qrTotalDisplay" style="color: #e06a20;">฿0</span></div>
            <div style="font-size: 0.9rem; color: #666; margin-top: 5px;">บัญชี: 099-999-9999 (ร้านคอฟฟี่ช็อป)</div>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="button" class="btn btn-outline" style="flex: 1;" onclick="closeQrModal()">ยกเลิก</button>
            <button type="button" class="btn btn-success" style="flex: 2; font-weight: bold;" onclick="confirmQrPayment()">✅ ยืนยันการโอนเงินแล้ว</button>
        </div>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .modal-overlay.show {
        opacity: 1;
    }
    .modal-content {
        background: #fdfbf9;
        padding: 30px;
        border-radius: 20px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }
    .modal-overlay.show .modal-content {
        transform: translateY(0);
    }
</style>
@endsection

@section('scripts')
<script>
    // ===== CART STATE =====
    let cart = [];
    let couponDiscount = 0;
    let couponCode = '';
    let paymentMethod = 'เงินสด';

    // ===== CATEGORY FILTER =====
    function filterCategory(category, btn) {
        document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('.product-card').forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // ===== ADD TO CART =====
    function addToCart(id, name, price, cardEl) {
        const existing = cart.find(item => item.product_id === id);

        if (existing) {
            existing.quantity++;
        } else {
            cart.push({
                product_id: id,
                name: name,
                price: price,
                quantity: 1,
                sweetness_level: 'หวานปกติ',
                temperature: 'เย็น',
                note: '',
            });
        }

        // Visual feedback
        cardEl.classList.add('added');
        setTimeout(() => cardEl.classList.remove('added'), 400);

        updateCart();
    }

    // ===== UPDATE CART UI =====
    function updateCart() {
        const itemsContainer = document.getElementById('cartItems');
        const footerEl = document.getElementById('cartFooter');
        const countEl = document.getElementById('cartCount');
        const clearBtn = document.getElementById('clearCartBtn');

        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        if(countEl) countEl.textContent = totalItems;
        
        // Sync with navbar badge
        const navBadge = document.getElementById('navCartCount');
        if (navBadge) navBadge.textContent = totalItems;
        
        // Save to localStorage
        localStorage.setItem('coffee_shop_cart', JSON.stringify(cart));

        // แก้บั๊กตรงนี้: หากไม่มีสินค้า ให้สร้าง HTML ความว่างเปล่าขึ้นมาใหม่
        if (cart.length === 0) {
            if(footerEl) footerEl.style.display = 'none';
            if(clearBtn) clearBtn.style.display = 'none';
            
            itemsContainer.innerHTML = `
                <div class="cart-empty" id="cartEmpty" style="margin-top: 50px; text-align: center;">
                    <p>ยังไม่มีสินค้าในตะกร้า<br><span style="font-size: 0.8rem; color: var(--coffee-300)">คลิกที่เมนูเพื่อเพิ่มสินค้า</span></p>
                </div>
            `;
            
            const submitBtn = document.getElementById('submitBtn');
            if(submitBtn) submitBtn.disabled = true;
            return;
        }

        if(footerEl) footerEl.style.display = '';
        if(clearBtn) clearBtn.style.display = '';
        const submitBtn = document.getElementById('submitBtn');
        if(submitBtn) submitBtn.disabled = false;

        let html = '';
        cart.forEach((item, index) => {
            const itemSubtotal = item.price * item.quantity;
            html += `
                <div class="cart-item" id="cartItem${index}">
                    <div class="cart-item-top">
                        <span class="cart-item-name">${item.name}</span>
                        <span class="cart-item-price">฿${itemSubtotal.toLocaleString()}</span>
                        <button class="cart-item-remove" onclick="removeItem(${index})" type="button">✕</button>
                    </div>
                    <div class="cart-item-options">
                        <select class="option-select" onchange="updateItemOption(${index}, 'sweetness_level', this.value)">
                            <option value="ไม่หวาน" ${item.sweetness_level === 'ไม่หวาน' ? 'selected' : ''}>ไม่หวาน</option>
                            <option value="หวานน้อย" ${item.sweetness_level === 'หวานน้อย' ? 'selected' : ''}>หวานน้อย</option>
                            <option value="หวานปกติ" ${item.sweetness_level === 'หวานปกติ' ? 'selected' : ''}>หวานปกติ</option>
                            <option value="หวานมาก" ${item.sweetness_level === 'หวานมาก' ? 'selected' : ''}>หวานมาก</option>
                        </select>
                        <select class="option-select" onchange="updateItemOption(${index}, 'temperature', this.value)">
                            <option value="ร้อน" ${item.temperature === 'ร้อน' ? 'selected' : ''}>ร้อน </option>
                            <option value="เย็น" ${item.temperature === 'เย็น' ? 'selected' : ''}>เย็น</option>
                            <option value="ปั่น" ${item.temperature === 'ปั่น' ? 'selected' : ''}>ปั่น</option>
                        </select>
                    </div>
                    <input type="text" class="cart-item-note" placeholder="หมายเหตุ..." value="${item.note}" onchange="updateItemOption(${index}, 'note', this.value)">
                    <div class="qty-controls">
                        <button class="qty-btn" onclick="changeQty(${index}, -1)" type="button">−</button>
                        <span class="qty-value">${item.quantity}</span>
                        <button class="qty-btn" onclick="changeQty(${index}, 1)" type="button">+</button>
                        <span style="margin-left: auto; font-size: 0.75rem; color: var(--coffee-400);">@฿${item.price.toLocaleString()}</span>
                    </div>
                </div>
            `;
        });

        itemsContainer.innerHTML = html;
        updateTotals();
        updateFormData();
    }

    // ===== CART OPERATIONS =====
    function removeItem(index) {
        cart.splice(index, 1);
        resetCoupon();
        updateCart();
    }

    function changeQty(index, delta) {
        cart[index].quantity += delta;
        if (cart[index].quantity <= 0) {
            cart.splice(index, 1);
        }
        resetCoupon();
        updateCart();
    }

    function updateItemOption(index, key, value) {
        cart[index][key] = value;
        updateFormData();
    }

    function clearCart() {
        if (confirm('ต้องการล้างตะกร้าทั้งหมด?')) {
            cart = [];
            resetCoupon();
            updateCart();
        }
    }

    // ===== TOTALS =====
    function updateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const total = subtotal - couponDiscount;

        document.getElementById('subtotalDisplay').textContent = `฿${subtotal.toLocaleString()}`;
        document.getElementById('totalDisplay').textContent = `฿${Math.max(0, total).toLocaleString()}`;

        if (couponDiscount > 0) {
            document.getElementById('discountRow').style.display = '';
            document.getElementById('discountDisplay').textContent = `-฿${couponDiscount.toLocaleString()}`;
        } else {
            document.getElementById('discountRow').style.display = 'none';
        }
    }

    // ===== COUPON =====
    function applyCoupon() {
        const code = document.getElementById('couponCode').value.trim().toUpperCase();
        const msgEl = document.getElementById('couponMessage');

        if (!code) {
            msgEl.textContent = 'กรุณากรอกรหัสคูปอง';
            msgEl.className = 'coupon-message error';
            return;
        }

        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        fetch('{{ route("orders.apply-coupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ code: code, subtotal: subtotal }),
        })
        .then(res => res.json())
        .then(data => {
            if (data.valid) {
                couponDiscount = data.discount;
                couponCode = data.coupon_code;
                msgEl.textContent = `✅ ${data.message} (ลด ฿${data.discount.toLocaleString()})`;
                msgEl.className = 'coupon-message success';
                document.getElementById('formCouponCode').value = couponCode;
            } else {
                couponDiscount = 0;
                couponCode = '';
                msgEl.textContent = `❌ ${data.message}`;
                msgEl.className = 'coupon-message error';
                document.getElementById('formCouponCode').value = '';
            }
            updateTotals();
        })
        .catch(() => {
            msgEl.textContent = '❌ เกิดข้อผิดพลาด';
            msgEl.className = 'coupon-message error';
        });
    }

    function resetCoupon() {
        couponDiscount = 0;
        couponCode = '';
        document.getElementById('couponCode').value = '';
        document.getElementById('couponMessage').textContent = '';
        document.getElementById('formCouponCode').value = '';
    }

    // ===== PAYMENT =====
    function selectPayment(method, btn) {
        paymentMethod = method;
        document.querySelectorAll('.payment-option').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        document.getElementById('formPaymentMethod').value = method;
    }

    // ===== CUSTOMER =====
    function toggleCustomer() {
        const fields = document.getElementById('customerFields');
        const arrow = document.getElementById('customerArrow');
        fields.classList.toggle('show');
        arrow.textContent = fields.classList.contains('show') ? '▾' : '▸';
    }

    // ===== FORM =====
    function updateFormData() {
        document.getElementById('formCustomerName').value = document.getElementById('customerName')?.value || '';
        document.getElementById('formCustomerPhone').value = document.getElementById('customerPhone')?.value || '';

        const formItems = document.getElementById('formItems');
        formItems.innerHTML = '';

        cart.forEach((item, i) => {
            formItems.innerHTML += `
                <input type="hidden" name="items[${i}][product_id]" value="${item.product_id}">
                <input type="hidden" name="items[${i}][quantity]" value="${item.quantity}">
                <input type="hidden" name="items[${i}][sweetness_level]" value="${item.sweetness_level}">
                <input type="hidden" name="items[${i}][temperature]" value="${item.temperature}">
                <input type="hidden" name="items[${i}][note]" value="${item.note}">
            `;
        });
    }

    // Sync customer name/phone on change and init cart
    document.addEventListener('DOMContentLoaded', () => {
        const nameInput = document.getElementById('customerName');
        const phoneInput = document.getElementById('customerPhone');
        if (nameInput) nameInput.addEventListener('input', updateFormData);
        if (phoneInput) phoneInput.addEventListener('input', updateFormData);
        
        // Load cart from localStorage
        const storedCart = localStorage.getItem('coffee_shop_cart');
        if (storedCart) {
            try {
                cart = JSON.parse(storedCart);
                if (cart.length > 0) {
                    updateCart();
                }
            } catch (e) {
                cart = [];
            }
        }
    });

    // ===== QR MODAL =====
    let qrConfirmed = false;

    function showQrModal() {
        const total = document.getElementById('totalDisplay').textContent;
        document.getElementById('qrTotalDisplay').textContent = total;
        
        const modal = document.getElementById('qrModal');
        modal.style.display = 'flex';
        // Trigger reflow for transition
        void modal.offsetWidth;
        modal.classList.add('show');
    }

    function closeQrModal() {
        const modal = document.getElementById('qrModal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    function confirmQrPayment() {
        qrConfirmed = true;
        closeQrModal();
        localStorage.removeItem('coffee_shop_cart');
        document.getElementById('orderForm').submit();
    }

    // Submit form — update form data first
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        updateFormData();
        if (cart.length === 0) {
            e.preventDefault();
            alert('กรุณาเพิ่มสินค้าลงตะกร้า');
        } else {
            if (paymentMethod === 'โอนเงิน' && !qrConfirmed) {
                e.preventDefault();
                showQrModal();
            } else {
                // Clear localStorage upon successful submission
                localStorage.removeItem('coffee_shop_cart');
            }
        }
    });
</script>
@endsection