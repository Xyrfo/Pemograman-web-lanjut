<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Kolom yang boleh diisi secara massal.
     * Sesuaikan dengan field di migrasi tadi.
     */
    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'stock',
        'image',
        'is_active',
        'is_featured',
    ];

    /**
     * Casting tipe data (Optional tapi disarankan)
     * Agar Laravel otomatis mengubah string dari DB menjadi tipe data yang benar.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'integer',
        'stock' => 'integer',
    ];

    /**
     * Accessor untuk mendapatkan URL gambar produk
     * Ini membantu ImageColumn di Filament mengakses gambar dengan benar
     */
    protected function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/products/' . $this->image);
        }
        return null;
    }
}
