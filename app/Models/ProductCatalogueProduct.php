<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCatalogueProduct extends Model
{
    use HasFactory;

    protected $table = 'product_catalogue_product';

    protected $fillable = [
        'product_id',
        'product_catalogue_id',
    ];
}