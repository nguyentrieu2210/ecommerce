<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'keyword',
        'description',
        'album',
        'model_id',
        'model',
        'publish',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'album' => 'array',
        'model_id' => 'array'
    ];
}