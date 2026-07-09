<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    // 1. ตรวจสอบข้อมูลที่รับมาจากหน้าฟอร์มเบลด
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $remember = $request->has('remember');

    // 2. ตรวจสอบการตรวจสอบสิทธิ์เข้าสู่ระบบ
    if (auth()->attempt($credentials, $remember)) {
        $request->session()->regenerate();

        /**
         * 🚀 [จุดแก้ไขสำคัญ] เช็กสถานะสิทธิ์ของผู้ใช้งาน
         * สมมติว่าในตาราง users เพื่อนของคุณเก็บคอลัมน์สิทธิ์แอดมินด้วยฟิลด์ชื่อ 'role' หรือ 'is_admin'
         */
        $user = auth()->user();

        // 🟢 เงื่อนไข: ถ้าเป็น Admin ให้ดีดตัวไปหน้าจัดการเมนูกาแฟของคุณทันที
        if ($user->is_admin == 1 || $user->role === 'admin') { 
            return redirect()->route('admin.menu.index');
        }

        // 🔵 เงื่อนไขทั่วไป: ถ้าเป็นลูกค้าหรือ Guest ปกติ ให้เด้งไปหน้าสั่งออเดอร์/หน้าร้าน POS
        return redirect()->intended(route('orders.index'));
    }

    // 🔴 กรณีรหัสผ่านผิดพลาด ส่งข้อความกลับไปแจ้งเตือนในหน้าเบลด
    return back()->withErrors([
        'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้องในระบบ',
    ])->withInput($request->only('email', 'remember'));
}
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        Auth::login($user);

        return redirect('/');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with('success', 'อัปเดตข้อมูลส่วนตัวเรียบร้อยแล้ว');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
