<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_phone',
        'coupon_id',
        'subtotal',
        'discount_amount',
        'total',
        'status',
        'payment_method',
        'payment_slip',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * สร้างเลขออเดอร์ format: CF-YYYYMMDD-XXXX
     */
    public static function generateOrderNumber(): string
    {
        $today = now()->format('Ymd');
        $prefix = "CF-{$today}-";

        $lastOrder = static::where('order_number', 'like', "{$prefix}%")
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }

    /**
     * รายการสินค้าในออเดอร์
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * คูปองที่ใช้ (ถ้ามี)
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * ผู้ใช้งานที่สั่งออเดอร์
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * สี badge ตาม status
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'amber',
            'paid' => 'emerald',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    /**
     * ชื่อสถานะภาษาไทย
     */
    public function getStatusThaiAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'รอชำระ',
            'paid' => 'ชำระแล้ว',
            'cancelled' => 'ยกเลิก',
            default => $this->status,
        };
    }
}
