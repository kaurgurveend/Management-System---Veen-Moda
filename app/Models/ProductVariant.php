<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    // Tambahkan ini agar data warna dan stok bisa disimpan
    protected $fillable = ['product_id', 'color', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sales()
    {
        return $this->hasMany(Sales::class, 'product_variant_id');
    }

    /**
     * Hitung total jumlah yang sudah terjual
     */
    public function getTotalSoldAttribute()
    {
        return $this->sales()->sum('quantity');
    }
}