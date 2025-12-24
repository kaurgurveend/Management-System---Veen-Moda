<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Tambahkan ini agar bisa simpan data
    protected $fillable = ['category_id', 'name', 'price'];

    // Relasi ke tabel varian warna
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}