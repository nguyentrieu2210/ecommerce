<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'model',
        'model_id',
        'history_id',
        'amount',
        'payment_method',
        'reference_code',
        'recorded_at',
        'detail',
        'content',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'detail' => 'array'
    ];

    public function history () {
        return $this->belongsTo(History::class);
    }
}