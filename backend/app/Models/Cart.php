<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    protected $primaryKey = 'cart_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'total_quantity',
        'total_price',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cart) {
            if (empty($cart->cart_id)) {
                $cart->cart_id = Str::uuid()->toString();
            }
        });
    }

    // Relationship with cart items
    public function items()
    {
        return $this->hasMany(Cart_item::class, 'cart_id', 'cart_id');
    }
}
