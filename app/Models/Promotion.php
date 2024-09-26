<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'detail',
        'start_date',
        'end_date',
        'never_end_date',
        'publish',
        'method',
        'model',
        'discount_value',
        'discount_type',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'detail' => 'array',
    ];

    public function products () {
        return $this->belongsToMany(Product::class, 'promotion_product_variant', 'promotion_id', 'product_id')->withPivot(
            'product_variant_id'
        );
    }

    public function productCatalogues () {
        return $this->belongsToMany(ProductCatalogue::class, 'promotion_product_catalogue', 'promotion_id', 'product_catalogue_id');
    }
}