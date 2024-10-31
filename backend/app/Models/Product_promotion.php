<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product_promotion extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_promotion_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_id',
        'promotion_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($productPromotion) {
            if (empty($productPromotion->product_promotion_id)) {
                $productPromotion->product_promotion_id = Str::uuid()->toString();
            }
        });
    }
}
