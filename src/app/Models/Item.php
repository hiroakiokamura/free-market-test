<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_path',
        'status', // 'on_sale', 'sold_out'
        'user_id',
    ];

    /**
     * 出品者のリレーション
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * この商品の購入情報
     */
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    /**
     * 商品が販売中かどうか
     */
    public function isOnSale()
    {
        return $this->status === 'on_sale';
    }
} 