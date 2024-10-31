<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Promotion extends Model
{
    use HasFactory;

    protected $primaryKey = 'promotion_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'type',
        'discount_percentage',
        'start_date',
        'end_date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($promotion) {
            if (empty($promotion->promotion_id)) {
                $promotion->promotion_id = Str::uuid()->toString();
            }
        });
    }
}
