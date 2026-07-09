<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Coffee Shop POS') — ☕ ร้านกาแฟ</title>
    <meta name="description" content="ระบบจัดการออเดอร์ร้านกาแฟ">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Thai:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('styles')
</head>

<body>
    <nav class="navbar">
        <a href="{{ route('menu') }}" class="navbar-brand">
            <span class="logo">☕</span>
            <span>Coffee Shop</span>
        </a>
        <ul class="navbar-nav">
            {{-- ===== เมนูสำหรับทุกคน ===== --}}


            @guest
                {{-- ===== Guest: เข้าสู่ระบบ / สมัครสมาชิก ===== --}}
                <li>
                    <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">
                        🔐 เข้าสู่ระบบ
                    </a>
                </li>
            @else
                {{-- ===== ล็อกอินแล้ว: ทั้ง Customer & Admin ===== --}}
                <li>
                    <a href="{{ route('orders.index') }}"
                        class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                        🏠 หน้าแรก
                    </a>
                </li>
                <li>
                    <a href="{{ route('orders.create') }}"
                        class="nav-link {{ request()->routeIs('orders.create') ? 'active' : '' }}">
                        ☕ สั่งเครื่องดื่ม
                    </a>
                </li>

                @if (auth()->user()->isAdmin())
                    {{-- ===== Admin Only ===== --}}
                    <li>
                        <a href="{{ route('admin.menu.index') }}"
                            class="nav-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                            ⚙️ จัดการเมนู
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('coupons.index') }}"
                            class="nav-link {{ request()->routeIs('coupons.*') ? 'active' : '' }}">
                            🎟️ คูปอง
                        </a>
                    </li>
                @endif

                <li>
                    <a href="javascript:void(0);" onclick="toggleCart()" class="nav-link" style="color: var(--coffee-100);">
                        🛒 ตะกร้า <span id="navCartCount" class="badge"
                            style="background: var(--danger); color: white; margin-left: 4px;">0</span>
                    </a>
                </li>

                {{-- ===== User info + Logout ===== --}}
                <li style="display: flex; align-items: center; gap: 8px;">
                    <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}"
                        style="display: flex; align-items: center; gap: 4px;">
                        👤 {{ auth()->user()->name }}
                        @if (auth()->user()->isAdmin())
                            <span
                                style="background: #dc3545; color: white; font-size: 0.65rem; padding: 2px 6px; border-radius: 10px;">ADMIN</span>
                        @endif
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-logout" title="ออกจากระบบ">
                            <span style="font-size: 1.1rem;">🚪</span> ออกจากระบบ
                        </button>
                    </form>
                </li>
            @endguest
        </ul>
    </nav>

    <main class="main-content">
        @if (session('success'))
            <div class="flash-message flash-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="flash-message flash-error">
                ❌ {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const storedCart = localStorage.getItem('coffee_shop_cart');
            if (storedCart) {
                try {
                    const cartArr = JSON.parse(storedCart);
                    const totalItems = cartArr.reduce((sum, item) => sum + item.quantity, 0);
                    const navBadge = document.getElementById('navCartCount');
                    if (navBadge) navBadge.textContent = totalItems;
                } catch (e) {}
            }
        });
    </script>
</body>

</html>
