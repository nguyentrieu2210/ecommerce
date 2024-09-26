<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class AttributeCatalogue extends Model
{
    use HasFactory, NodeTrait, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'canonical',
        'level',
        'publish',
        'image',
        'icon',
        'follow',
        'order',
        'user_id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function attributes () {
        return $this->belongsToMany(Attribute::class, 'attribute_catalogue_attribute', 'attribute_catalogue_id', 'attribute_id');
    }
}