<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class PostCatalogue extends Model
{
    use HasFactory, NodeTrait, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'content',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'canonical',
        'level',
        'publish',
        'image',
        'album',
        'icon',
        'follow',
        'order',
        'user_id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function posts () {
        return $this->belongsToMany(Post::class, 'post_catalogue_post', 'post_catalogue_id', 'post_id');
    }
}