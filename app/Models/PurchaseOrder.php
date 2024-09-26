<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'code',
        'reference_code',
        'description',
        'price_total',
        'quantity_total',
        'discount_value',
        'discount_type',
        'status',
        'user_id',
        'supplier_id',
        'warehouse_id',
        'expected_day',
        'created_at',
        'updated_at',
    ];

    public function users () {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function warehouses () {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function receiveInventories () {
        return $this->hasMany(ReceiveInventory::class, 'purchase_order_id', 'id');
    }

    public function suppliers () {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function products () {
        return $this->belongsToMany(Product::class, 'product_purchase_order', 'purchase_order_id', 'product_id')->withPivot(
            'product_variant_id',
            'name',
            'name_variant',
            'sku',
            'quantity',
            'cost_price',
            'discount_value',
            'discount_type',
            'image',
            'quantity_received',
            'quantity_rejected'
        );
    }
}