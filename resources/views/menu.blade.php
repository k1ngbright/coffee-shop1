@extends('layouts.app')

@section('title', 'เมนูเครื่องดื่ม - Coffee Shop')

@section('styles')
<style>
    :root {
        --cs-orange: #FF8C42;
        --cs-orange-light: #FFB877;
        --cs-orange-dark: #E06A20;
        --cs-cream: #FFF9F3;
        --cs-gradient: linear-gradient(135deg, var(--cs-orange) 0%, var(--cs-orange-light) 100%);
    }

    .menu-hero {
        position: relative;
        background:
            linear-gradient(135deg, rgba(224, 106, 32, 0.75), rgba(255, 140, 66, 0.55)),
            url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&q=80') center/cover;
        color: #fff;
        padding: 80px 20px;
        border-radius: 24px;
        text-align: center;
        margin-bottom: 40px;
        box-shadow: 0 20px 45px rgba(224, 106, 32, 0.25);
        overflow: hidden;
        animation: heroFadeIn 0.9s ease both;
    }
    .menu-hero h1 {
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 12px;
        text-shadow: 0 4px 14px rgba(0,0,0,0.25);
    }
    .menu-hero p {
        font-size: 1.15rem;
        color: #fff5ec;
        margin-bottom: 24px;
        opacity: 0.95;
    }
    @keyframes heroFadeIn {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .category-filter {
        display: flex;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }
    .category-btn {
        padding: 8px 20px;
        border-radius: 30px;
        border: 2px solid #FFE8D6;
        background: white;
        color: #4a3423;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: inherit;
    }
    .category-btn:hover, .category-btn.active {
        background: var(--cs-gradient);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(224, 106, 32, 0.3);
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 24px;
        padding-bottom: 40px;
    }

    .menu-card {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(224, 106, 32, 0.08);
        transition: transform 0.35s cubic-bezier(.22,1,.36,1), box-shadow 0.35s ease;
        border: 1px solid #FFE8D6;
        animation: cardIn 0.6s ease both;
    }
    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 30px rgba(224, 106, 32, 0.18);
        border-color: var(--cs-orange-light);
    }
    @keyframes cardIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .menu-card-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }
    .menu-card:hover .menu-card-img {
        transform: scale(1.05);
    }
    .menu-card-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #FFF1E3, #FFE3CC);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: var(--cs-orange);
        transition: transform 0.5s ease;
    }
    .menu-card:hover .menu-card-placeholder {
        transform: scale(1.05);
    }
    .menu-card-img-wrap {
        overflow: hidden;
        position: relative;
    }
    .menu-card-category {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(255, 140, 66, 0.9);
        color: white;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        backdrop-filter: blur(4px);
    }

    .menu-card-body {
        padding: 18px;
        text-align: center;
    }
    .menu-card-name {
        font-size: 1.15rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 6px;
    }
    .menu-card-desc {
        font-size: 0.85rem;
        color: #9a8f86;
        margin-bottom: 12px;
        line-height: 1.4;
    }
    .menu-card-price {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--cs-orange-dark);
        margin-bottom: 14px;
    }
    .menu-card-price::before {
        content: '฿';
        margin-right: 2px;
        opacity: 0.85;
    }

    .btn-login-to-order {
        display: inline-block;
        background: var(--cs-gradient);
        color: white;
        text-decoration: none;
        padding: 8px 22px;
        border-radius: 22px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 12px rgba(224, 106, 32, 0.25);
    }
    .btn-login-to-order:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(224, 106, 32, 0.35);
    }

    .menu-empty {
        text-align: center;
        padding: 60px 20px;
        background: #FFF9F3;
        border-radius: 18px;
    }
    .menu-empty span { font-size: 3rem; display: block; margin-bottom: 8px; }
    .menu-empty h4 { color: var(--cs-orange-dark); margin-top: 10px; }
    .menu-empty p { color: #9a8f86; }
</style>
@endsection

@section('content')
    <div class="menu-hero">
        <h1>☕ เมนูเครื่องดื่ม</h1>
        <p>เลือกดูเมนูเครื่องดื่มจากร้าน Coffee Shop ของเรา</p>
        @guest
            <a href="{{ route('login') }}" class="btn-login-to-order" style="font-size: 1.1rem; padding: 12px 30px;">
                🔐 เข้าสู่ระบบเพื่อสั่งซื้อ
            </a>
        @else
            <a href="{{ route('orders.create') }}" class="btn-login-to-order" style="font-size: 1.1rem; padding: 12px 30px;">
                🛒 เริ่มสั่งเครื่องดื่มเลย
            </a>
        @endguest
    </div>

    <div class="category-filter">
        <button class="category-btn active" onclick="filterMenu('all', this)">🍽️ ทั้งหมด</button>
        @foreach($categories as $cat)
            <button class="category-btn" onclick="filterMenu('{{ $cat }}', this)">
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

    <div class="menu-grid" id="menuGrid">
        @forelse($products as $index => $product)
            <div class="menu-card" data-category="{{ $product->category }}" style="animation-delay: {{ $index * 0.05 }}s">
                <div class="menu-card-img-wrap">
                    <span class="menu-card-category">{{ $product->category }}</span>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="menu-card-img">
                    @else
                        <div class="menu-card-placeholder">
                            @if($product->category === 'กาแฟ') ☕
                            @elseif($product->category === 'ชา') 🍵
                            @elseif($product->category === 'เครื่องดื่มอื่นๆ') 🧋
                            @elseif($product->category === 'เบเกอรี่') 🍞
                            @else 🍽️
                            @endif
                        </div>
                    @endif
                </div>
                <div class="menu-card-body">
                    <div class="menu-card-name">{{ $product->name }}</div>
                    @if($product->description)
                        <div class="menu-card-desc">{{ $product->description }}</div>
                    @endif
                    <div class="menu-card-price">{{ number_format($product->price, 0) }}</div>
                    @guest
                        <a href="{{ route('login') }}" class="btn-login-to-order">🔐 เข้าสู่ระบบเพื่อสั่ง</a>
                    @else
                        <a href="{{ route('orders.create') }}" class="btn-login-to-order">🛒 สั่งเลย</a>
                    @endguest
                </div>
            </div>
        @empty
            <div class="menu-empty">
                <span>📝</span>
                <h4>ยังไม่มีเมนูในระบบ</h4>
                <p>กรุณารอสักครู่ ทางร้านกำลังเพิ่มเมนูอยู่</p>
            </div>
        @endforelse
    </div>
@endsection

@section('scripts')
<script>
    function filterMenu(category, btn) {
        document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('.menu-card').forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
@endsection
