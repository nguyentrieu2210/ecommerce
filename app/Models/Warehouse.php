<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'phone',
        'address',
        'user_id',
        'description',
        'publish',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function products () {
        return $this->belongsToMany(Product::class, 'product_warehouse', 'warehouse_id', 'product_id')->withPivot([
            'product_variant_id',
            'quantity',
            'cost_price',
            'stock',
            'incoming'
        ]);
    }

    public function users () {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}