<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'keyword',
        'item',
        'setting',
        'publish',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'item' => 'array',
        'setting' => 'array'
    ];
}