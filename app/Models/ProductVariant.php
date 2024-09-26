<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'sku',
        'barcode',
        'price',
        'cost',
        'weight',
        'mass',
        'publish',
        'created_at',
        'updated_at',
    ];

    public function attributes () {
        return $this->belongsToMany(Attribute::class, 'product_variant_attribute', 'product_variant_id', 'attribute_id');
    }
}