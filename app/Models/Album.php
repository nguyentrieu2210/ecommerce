<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'album',
        'product_id',
        'attribute_catalogue_id',
        'attribute_id',
        'attribute_name',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'album' => 'array'
    ];

    public function products () {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}