<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPurchaseOrder extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'product_purchase_order';

    protected $fillable = [
        'product_id',
        'purchase_order_id',
        'product_variant_id',
        'name',
        'name_variant',
        'sku',
        'quantity',
        'discount_value',
        'discount_type',
        'image',
        'cost_price',
        'quantity_received',
        'quantity_rejected'
    ];
}