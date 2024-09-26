<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveInventory extends Model
{
    use HasFactory;

    protected $table = 'receive_inventories';

    protected $fillable = [
        'code',
        'reference_code',
        'description',
        'price_total',
        'quantity_total',
        'discount_value',
        'discount_type',
        'status_returned',
        'import_fee',
        'status_receive_inventory',
        'status_payment',
        'purchase_order_id',
        'user_id',
        'supplier_id',
        'warehouse_id',
        'expected_day',
        'created_at',
        'updated_at',
    ];

    // protected $casts = [
    //     'import_fee' => 'array',
    // ];

    public function users () {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function warehouses () {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function purchaseOrders () {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }

    public function suppliers () {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function products () {
        return $this->belongsToMany(Product::class, 'product_receive_inventory', 'receive_inventory_id', 'product_id')->withPivot(
            'product_variant_id',
            'name',
            'name_variant',
            'sku',
            'quantity',
            'cost_price',
            'discount_value',
            'discount_type',
            'image',
            'quantity_rejected',
            'rejection_reason'
        );
    }
}