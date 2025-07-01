<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'postal_code',
        'address',
        'building',
        'profile_photo_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The attributes that should be appended.
     *
     * @var array<string, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the items that belong to the user.
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Get the purchases that belong to the user.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * 購入した商品一覧
     */
    public function purchasedItems()
    {
        return $this->belongsToMany(Item::class, 'purchases');
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return Storage::url($this->profile_photo_path);
        }
        return asset('images/default-profile.png');
    }
}
