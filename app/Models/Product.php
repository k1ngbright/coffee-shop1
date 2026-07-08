<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'image',
        'category',
        'description',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
    ];

    /**
     * Scope: เฉพาะสินค้าที่เปิดใช้งาน
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope: filter ตาม category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * สินค้านี้อยู่ใน order items ไหนบ้าง
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
