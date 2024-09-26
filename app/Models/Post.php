<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'content',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'canonical',
        'post_catalogue_id',
        'model_id',
        'publish',
        'image',
        'album',
        'like_counter',
        'icon',
        'follow',
        'order',
        'user_id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $casts = [
        'album' => 'array'
    ];

    public function post_catalogues () {
        return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_post', 'post_id', 'post_catalogue_id');
    }

    public function users () {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}