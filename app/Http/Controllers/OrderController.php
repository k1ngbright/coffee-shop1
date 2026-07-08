<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * แสดงรายการออเดอร์ทั้งหมด
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        $orders = Order::with(['items.product', 'coupon'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $recommendedProducts = Product::where('status', 1)->inRandomOrder()->take(4)->get();

        $bestSellingProducts = Product::where('status', 1)
            ->withSum('orderItems as total_sold', 'quantity')
            ->having('total_sold', '>', 0)
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('orders.index', compact('orders', 'status', 'recommendedProducts', 'bestSellingProducts'));
    }

    /**
     * หน้า POS สำหรับสร้างออเดอร์
     */
    public function create()
    {
        $products = Product::active()->orderBy('category')->orderBy('name')->get();
        $categories = Product::active()->distinct()->pluck('category')->filter()->values();

        return view('orders.create', compact('products', 'categories'));
    }

    /**
     * บันทึกออเดอร์ใหม่
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.sweetness_level' => 'required|in:ไม่หวาน,หวานน้อย,หวานปกติ,หวานมาก',
            'items.*.temperature' => 'nullable|in:ร้อน,เย็น,ปั่น',
            'items.*.note' => 'nullable|string|max:255',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'coupon_code' => 'nullable|string',
            'payment_method' => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($request) {
            // คำนวณ subtotal
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $itemSubtotal,
                    'sweetness_level' => $item['sweetness_level'],
                    'temperature' => $item['temperature'] ?? null,
                    'note' => $item['note'] ?? null,
                ];
            }

            // คำนวณส่วนลดจากคูปอง
            $discountAmount = 0;
            $couponId = null;

            if ($request->coupon_code) {
                $coupon = Coupon::where('code', $request->coupon_code)->first();
                if ($coupon) {
                    $validation = $coupon->isValid($subtotal);
                    if ($validation['valid']) {
                        $discountAmount = $coupon->calculateDiscount($subtotal);
                        $couponId = $coupon->id;
                        $coupon->increment('used_count');
                    }
                }
            }

            $total = $subtotal - $discountAmount;

            // สร้าง Order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'coupon_id' => $couponId,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
            ]);

            // สร้าง Order Items
            foreach ($itemsData as $itemData) {
                $order->items()->create($itemData);
            }

            return redirect()->route('orders.show', $order)
                ->with('success', 'สร้างออเดอร์ ' . $order->order_number . ' สำเร็จ!');
        });
    }

    /**
     * แสดงรายละเอียดออเดอร์
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'coupon']);
        return view('orders.show', compact('order'));
    }

    /**
     * แสดงใบเสร็จรับเงิน
     */
    public function receipt(Order $order)
    {
        $order->load(['items.product', 'coupon']);
        return view('orders.receipt', compact('order'));
    }

    /**
     * อัพเดทสถานะออเดอร์
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'อัพเดทสถานะออเดอร์ ' . $order->order_number . ' เป็น "' . $order->status_thai . '" สำเร็จ!');
    }

    /**
     * ตรวจสอบและคำนวณส่วนลดคูปอง (AJAX)
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'ไม่พบคูปองนี้',
            ]);
        }

        $validation = $coupon->isValid($request->subtotal);

        if (!$validation['valid']) {
            return response()->json($validation);
        }

        $discount = $coupon->calculateDiscount($request->subtotal);

        return response()->json([
            'valid' => true,
            'message' => 'ใช้คูปองสำเร็จ!',
            'discount' => $discount,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'coupon_code' => $coupon->code,
        ]);
    }
}
