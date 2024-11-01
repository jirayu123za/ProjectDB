<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Cart_item extends Model
{
    use HasFactory;

    protected $primaryKey = 'cart_item_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'unit_price',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cartItem) {
            if (empty($cartItem->cart_item_id)) {
                $cartItem->cart_item_id = Str::uuid()->toString();
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
