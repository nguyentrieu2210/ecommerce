<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'model_id',
        'user_id',
        'user_name',
        'content',
        'detail',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'detail' => 'array'
    ];

    public function users () {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function payment_record () {
        return $this->hasOne(PaymentRecord::class);
    }
}