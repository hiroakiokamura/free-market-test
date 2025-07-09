<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'content'
    ];

    /**
     * このカテゴリーに属する商品
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_categories')
                    ->withTimestamps();
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
