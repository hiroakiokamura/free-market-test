<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand_name',
        'description',
        'price',
        'image_path',
        'status', // 'on_sale', 'sold', 'sold_out'
        'condition',
        'category', // カテゴリ情報を直接保存
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
            '新品' => '新品',
            '良好' => '良好',
            '傷あり' => '傷あり',
        ][$this->condition] ?? $this->condition;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * カテゴリー情報を取得（文字列として直接保存）
     */
    public function getCategoryAttribute($value)
    {
        return $value;
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->count();
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    public function isLikedBy($user): bool
    {
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }
} 