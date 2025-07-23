<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'price',
        'shipping_postal_code',
        'shipping_prefecture',
        'shipping_city',
        'shipping_address',
        'shipping_building',
        'payment_intent_id',
        'payment_method',
        'status'
    ];

    protected $dates = [
        'paid_at'
    ];

    /**
     * 購入者のリレーション
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 購入された商品のリレーション
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
} 