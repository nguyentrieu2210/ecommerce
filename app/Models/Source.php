<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Source extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'description',
        'keyword',
        'publish',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function customers () {
        return $this->hasMany(Customer::class, 'source_id', 'id');
    }
}