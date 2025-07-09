<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'brand_name',
        'description',
        'price',
        'image_path',
        'status', // 'on_sale', 'sold_out'
        'condition',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_categories')
                    ->withTimestamps();
    }

    /**
     * 商品の状態を日本語で取得
     */
    public function getConditionLabel()
    {
        return [
            'new' => '新品、未使用',
            'like_new' => '未使用に近い',
            'good' => '目立った傷や汚れなし',
            'fair' => 'やや傷や汚れあり',
            'poor' => '傷や汚れあり',
        ][$this->condition] ?? $this->condition;
    }
} 