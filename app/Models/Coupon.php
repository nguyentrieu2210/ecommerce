<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'detail',
        'start_date',
        'end_date',
        'never_end_date',
        'publish',
        'method',
        'discount_value',
        'discount_type',
        'max_discount',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'detail' => 'array',
    ];
}