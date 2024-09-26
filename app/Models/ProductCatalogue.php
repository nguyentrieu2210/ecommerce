<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class ProductCatalogue extends Model
{
    use HasFactory, NodeTrait, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'content',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'canonical',
        'level',
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

    protected $casts = [
        'album' => 'array'
    ];

    public function products () {
        return $this->belongsToMany(Product::class, 'product_catalogue_product', 'product_catalogue_id', 'product_id');
    }

    public function promotions () {
        return $this->belongsToMany(Promotion::class, 'promotion_product_catalogue', 'product_catalogue_id', 'promotion_id');
    }
}