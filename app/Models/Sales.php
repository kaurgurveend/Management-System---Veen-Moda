<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'user_id',
        'quantity',
        'price_per_unit',
        'total_price',
        'notes',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
