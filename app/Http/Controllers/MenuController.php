<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.menu.index', compact('products'));
    }

    public function create()
    {
        $categories = ['กาแฟ', 'ชา', 'เครื่องดื่มอื่นๆ', 'เบเกอรี่'];
        return view('admin.menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'    => 'required|string',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'category', 'price', 'description']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['status'] = $request->has('status') ? 1 : 0;

        Product::create($data);
        return redirect()->route('admin.menu.index')->with('success', 'เพิ่มเมนูใหม่สำเร็จแล้ว!');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.menu.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ['กาแฟ', 'ชา', 'เครื่องดื่มอื่นๆ', 'เบเกอรี่'];
        return view('admin.menu.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category'    => 'required|string',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);
        $data = $request->only(['name', 'category', 'price', 'description']);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['status'] = $request->has('status') ? 1 : 0;
        $product->update($data);

        return redirect()->route('admin.menu.index')->with('success', 'อัปเดตข้อมูลเมนูสำเร็จแล้ว!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'ลบรายการเมนูเรียบร้อยแล้ว!'
        ]);
    }
}