<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'album',
        'content',
        'star_rating',
        'customer_id',
        'model',
        'model_id',
        'likes_count',
        'name',
        'phone',
        'publish',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'album' => 'array'
    ];

    public function customers () {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}