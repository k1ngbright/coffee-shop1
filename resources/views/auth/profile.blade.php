@extends('layouts.app')

@section('title', 'โปรไฟล์ส่วนตัว - Coffee Shop')

@section('styles')
<style>
    .profile-container {
        max-width: 600px;
        margin: 4rem auto;
        background: white;
        padding: 2.5rem;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        border: 1px solid var(--coffee-100);
    }
    
    .profile-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .profile-avatar {
        width: 100px;
        height: 100px;
        background: var(--coffee-100);
        color: var(--coffee-700);
        font-size: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 1rem auto;
        border: 4px solid white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    .profile-header h1 {
        font-size: 1.75rem;
        color: var(--coffee-900);
        margin-bottom: 0.25rem;
    }
    
    .profile-header p {
        color: var(--coffee-500);
        font-size: 0.9rem;
    }

    .profile-role-badge {
        display: inline-block;
        background: var(--coffee-800);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 8px;
    }
    .profile-role-badge.admin {
        background: #dc3545;
    }
    
    .profile-details {
        margin-top: 2rem;
        border-top: 1px solid var(--coffee-100);
        padding-top: 1.5rem;
    }

    .detail-item {
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: var(--coffee-50);
        border-radius: var(--radius-sm);
    }

    .detail-label {
        font-weight: 600;
        color: var(--coffee-700);
    }

    .detail-value {
        color: var(--coffee-900);
        font-weight: 500;
    }

    .profile-actions {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
    }

    .btn-action {
        flex: 1;
        padding: 0.875rem;
        text-align: center;
        border-radius: var(--radius);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-history {
        background: var(--coffee-100);
        color: var(--coffee-800);
        border: 1px solid var(--coffee-200);
    }

    .btn-history:hover {
        background: var(--coffee-200);
    }

    .btn-logout {
        background: white;
        color: #dc3545;
        border: 1px solid #dc3545;
    }

    .btn-logout:hover {
        background: #f8d7da;
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-avatar">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <h1>{{ $user->name }}</h1>
        <p>{{ $user->email }}</p>
        
        @if($user->isAdmin())
            <span class="profile-role-badge admin">ผู้ดูแลระบบ (Admin)</span>
        @else
            <span class="profile-role-badge">ลูกค้า (Customer)</span>
        @endif
    </div>

    <div class="profile-details">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1rem; text-align: left;">
                <label style="display: block; font-weight: 600; color: var(--coffee-700); margin-bottom: 0.5rem;">ชื่อ-นามสกุล</label>
                <input type="text" name="name" value="{{ $user->name }}" style="width: 100%; padding: 0.75rem; border: 1px solid var(--coffee-200); border-radius: var(--radius-sm); font-family: inherit; outline: none;" required>
            </div>
            
            <div style="margin-bottom: 1rem; text-align: left;">
                <label style="display: block; font-weight: 600; color: var(--coffee-700); margin-bottom: 0.5rem;">เบอร์โทรศัพท์ (ใส่เพื่อให้ระบบจำตอนสั่งเครื่องดื่ม)</label>
                <input type="text" name="phone" value="{{ $user->phone }}" style="width: 100%; padding: 0.75rem; border: 1px solid var(--coffee-200); border-radius: var(--radius-sm); font-family: inherit; outline: none;" placeholder="เช่น 0812345678">
            </div>

            <div style="margin-bottom: 1rem; text-align: left;">
                <label style="display: block; font-weight: 600; color: var(--coffee-700); margin-bottom: 0.5rem;">อีเมล (เปลี่ยนไม่ได้)</label>
                <input type="email" value="{{ $user->email }}" style="width: 100%; padding: 0.75rem; border: 1px solid var(--coffee-200); border-radius: var(--radius-sm); background: #f8f9fa; font-family: inherit; outline: none;" disabled>
            </div>

            <button type="submit" class="btn-action" style="width: 100%; background: var(--coffee-800); color: white; border: none; cursor: pointer;">บันทึกข้อมูล</button>
        </form>

        <div style="margin-top: 1.5rem; border-top: 1px solid var(--coffee-100); padding-top: 1.5rem;">
            <div class="detail-item">
                <span class="detail-label">วันที่สมัครสมาชิก:</span>
                <span class="detail-value">{{ $user->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">ออเดอร์ทั้งหมด:</span>
                <span class="detail-value">{{ $user->orders()->count() }} รายการ</span>
            </div>
        </div>
    </div>

    <div class="profile-actions">
        <a href="{{ route('orders.index') }}" class="btn-action btn-history">ประวัติการสั่งซื้อ</a>
        
        <form method="POST" action="{{ route('logout') }}" style="flex: 1;">
            @csrf
            <button type="submit" class="btn-action btn-logout" style="width: 100%; font-family: inherit; font-size: inherit;">ออกจากระบบ</button>
        </form>
    </div>
</div>
@endsection
