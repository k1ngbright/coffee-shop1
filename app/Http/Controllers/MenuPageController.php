<?php

namespace App\Http\Controllers;

use App\Models\Product;

class MenuPageController extends Controller
{
    /**
     * หน้าเมนู public สำหรับ guest ดูได้โดยไม่ต้อง login
     */
    public function index()
    {
        $products = Product::where('status', 1)->orderBy('category')->orderBy('name')->get();
        $categories = Product::where('status', 1)->distinct()->pluck('category')->filter()->values();

        return view('menu', compact('products', 'categories'));
    }  
}
