<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * แสดงรายการคูปองทั้งหมด
     */
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();

        return view('coupons.index', compact('coupons'));
    }

    /**
     * หน้าฟอร์มเพิ่มคูปอง
     */
    public function create()
    {
        return view('coupons.create');
    }

    /**
     * บันทึกคูปองใหม่
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'expire_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'code.required' => 'กรุณากรอกรหัสคูปอง',
            'code.unique' => 'รหัสคูปองนี้มีอยู่แล้ว',
            'discount_type.required' => 'กรุณาเลือกประเภทส่วนลด',
            'discount_value.required' => 'กรุณากรอกมูลค่าส่วนลด',
            'discount_value.numeric' => 'มูลค่าส่วนลดต้องเป็นตัวเลข',
            'expire_date.after_or_equal' => 'วันหมดอายุต้องไม่ก่อนวันเริ่มใช้งาน',
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_amount' => $request->min_order_amount,
            'max_discount_amount' => $request->max_discount_amount,
            'usage_limit' => $request->usage_limit,
            'used_count' => 0,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('coupons.index')->with('success', 'เพิ่มคูปองเรียบร้อยแล้ว');
    }

    /**
     * หน้าฟอร์มแก้ไขคูปอง
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);

        return view('coupons.edit', compact('coupon'));
    }

    /**
     * อัปเดตคูปอง
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'expire_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'code.required' => 'กรุณากรอกรหัสคูปอง',
            'code.unique' => 'รหัสคูปองนี้มีอยู่แล้ว',
            'discount_type.required' => 'กรุณาเลือกประเภทส่วนลด',
            'discount_value.required' => 'กรุณากรอกมูลค่าส่วนลด',
            'expire_date.after_or_equal' => 'วันหมดอายุต้องไม่ก่อนวันเริ่มใช้งาน',
        ]);

        $coupon->update([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_amount' => $request->min_order_amount,
            'max_discount_amount' => $request->max_discount_amount,
            'usage_limit' => $request->usage_limit,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('coupons.index')->with('success', 'แก้ไขคูปองเรียบร้อยแล้ว');
    }

    /**
     * ลบคูปอง
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('coupons.index')->with('success', 'ลบคูปองเรียบร้อยแล้ว');
    }
}