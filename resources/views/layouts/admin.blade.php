<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Coffee Shop</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #fdfbf9;
            font-family: 'Sarabun', sans-serif;
        }

        /* ===== COFFEE ADMIN LAYOUT WITH SIDEBAR ===== */
        .admin-layout-wrapper {
            display: flex;
            min-height: 100vh;
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
        
        .sidebar-brand a {
            text-decoration: none;
            color: white;
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
            margin-left: 260px; /* เว้นระยะหลบแนว Sidebar */
            padding: 40px;
            min-width: 0;
        }

        /* Responsive สำหรับหน้าจอขนาดเล็ก */
        @media (max-width: 992px) {
            .admin-sidebar { width: 70px; }
            .sidebar-brand span, .sidebar-user-profile .user-info-text, .sidebar-menu-item a span, .btn-sidebar-logout span {
                display: none;
            }
            .admin-main-content { margin-left: 70px; padding: 20px; }
            .sidebar-brand, .sidebar-menu-list a, .sidebar-user-profile { justify-content: center; }
        }
    </style>
</head>
<body>

    <div class="admin-layout-wrapper">
        
        <nav class="admin-sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('admin.menu.home') }}">
                    <i class="bi bi-cup-hot-fill" style="color: #c59b27;"></i>
                    <span>Coffee Admin</span>
                </a>
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
                <li class="sidebar-menu-item {{ Request::is('admin/home') ? 'active' : '' }}">
                    <a href="{{ route('admin.menu.home') }}">
                        <i class="bi bi-speedometer2"></i> <span>แดชบอร์ด</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ Request::is('admin/menu*') ? 'active' : '' }}">
                    <a href="{{ route('admin.menu.index') }}">
                        <i class="bi bi-egg-fried"></i> <span>จัดการเมนูสินค้า</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ Request::is('coupons*') ? 'active' : '' }}">
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
            @yield('content')
        </main>

    </div>

</body>
</html>