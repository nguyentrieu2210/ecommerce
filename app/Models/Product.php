<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'barcode',
        'weight',
        'mass',
        'measure',
        'specifications',
        'questions_answers',
        'description',
        'content',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'canonical',
        'allow_to_sell',
        'product_catalogue_id',
        'attribute_catalogue',
        'attribute',
        'variant',
        'cost',
        'price',
        'input_tax',
        'output_tax',
        'tax_status',
        'publish',
        'image',
        'album',
        'icon',
        'follow',
        'order',
        'user_id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $casts = [
        'album' => 'array',
        'questions_answers' => 'array',
        'specifications' => 'array',
        'variant' => 'array',
        'attribute_catalogue' => 'array',
        'attribute' => 'array',
    ];

    public function product_catalogues () {
        return $this->belongsToMany(ProductCatalogue::class, 'product_catalogue_product', 'product_id', 'product_catalogue_id');
    }

    public function posts () {
        return $this->belongsToMany(Post::class, 'product_post', 'product_id', 'post_id');
    }

    public function warehouses () {
        return $this->belongsToMany(Warehouse::class, 'product_warehouse', 'product_id', 'warehouse_id')->withPivot(
            'product_variant_id',
            'quantity',
            'cost_price',
            'stock',
            'incoming'
        );
    }

    public function product_variants() {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    public function albums () {
        return $this->hasMany(Album::class, 'product_id', 'id');
    }
    public function promotions() {
        return $this->belongsToMany(Promotion::class, 'promotion_product_variant', 'product_id', 'promotion_id')->withPivot(
            'product_variant_id'
        );
    }
}