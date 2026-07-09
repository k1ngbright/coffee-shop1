@extends('layouts.app')

@section('title', 'เข้าสู่ระบบ - Coffee Shop')

@section('styles')
<style>
    .auth-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 200px);
        padding: 2rem 1rem;
    }

    .auth-container {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        padding: 3rem 2.5rem;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(74, 52, 35, 0.08), 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid rgba(111, 78, 55, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .auth-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--coffee-400), var(--coffee-700));
    }
    
    .auth-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    
    .auth-header .logo-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--coffee-50);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);
        color: var(--coffee-700);
    }
    
    .auth-header a:hover .logo-icon {
        transform: scale(1.1);
        box-shadow: inset 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .auth-header h1 {
        font-size: 1.8rem;
        color: var(--coffee-900);
        margin-bottom: 0.5rem;
        font-weight: 700;
    }
    
    .auth-header p {
        color: var(--coffee-500);
        font-size: 0.95rem;
    }
    
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--coffee-800);
        font-size: 0.9rem;
    }
    
    .form-control {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 2px solid var(--coffee-100);
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #faf8f6;
        color: var(--coffee-900);
    }
    
    .form-control::placeholder {
        color: #c4b5a7;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--coffee-400);
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(111, 78, 55, 0.08);
    }
    
    .btn-login {
        width: 100%;
        padding: 1rem;
        background: var(--coffee-700);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.05rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }
    
    .btn-login:hover {
        background: var(--coffee-800);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(74, 52, 35, 0.2);
    }
    
    .btn-login:active {
        transform: translateY(0);
    }
    
    .auth-footer {
        margin-top: 2rem;
        text-align: center;
        font-size: 0.95rem;
        color: var(--coffee-600);
        padding-top: 1.5rem;
        border-top: 1px solid var(--coffee-100);
    }
    
    .auth-footer a {
        color: var(--coffee-700);
        font-weight: 700;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .auth-footer a:hover {
        color: var(--coffee-900);
        text-decoration: underline;
    }
    
    .alert-error {
        background: #fff5f5;
        color: #c53030;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        border: 1px solid #fed7d7;
        box-shadow: 0 2px 5px rgba(197, 48, 48, 0.05);
    }
    
    .alert-error ul {
        margin: 0;
        padding-left: 1.25rem;
    }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="auth-container">
        <div class="auth-header">
            <a href="{{ route('menu') }}" style="text-decoration: none;">
                <div class="logo-icon" style="transition: transform 0.2s;">☕</div>
            </a>
            <h1>เข้าสู่ระบบ</h1>
            <p>ยินดีต้อนรับกลับสู่ Coffee Shop</p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">อีเมล</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="your@email.com">
            </div>
            
            <div class="form-group">
                <label for="password">รหัสผ่าน</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            
            <div class="form-group" style="display: flex; align-items: center; justify-content: space-between; margin-top: 1rem;">
                <label for="remember" style="margin-bottom: 0; font-weight: normal; color: var(--coffee-600); display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" id="remember" name="remember" style="accent-color: var(--coffee-700); width: 16px; height: 16px; cursor: pointer;">
                    จดจำฉันไว้ในระบบ
                </label>
                {{-- Uncomment if you add forgot password later
                <a href="#" style="color: var(--coffee-600); font-size: 0.85rem; text-decoration: none;">ลืมรหัสผ่าน?</a> --}}
            </div>

            <button type="submit" class="btn-login">
                <span>เข้าสู่ระบบ</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
            </button>
        </form>
        
        <div class="auth-footer">
            ยังไม่มีบัญชีผู้ใช้? <a href="{{ route('register') }}">สมัครสมาชิกเลย</a>
        </div>
    </div>
</div>
@endsection
