<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Link extends Model
{
    use HasFactory, NodeTrait;

    protected $fillable = [
        'name',
        'canonical',
        'level',
        'image',
        'icon',
        'follow',
        'order',
        'menu_id',
        'detail',
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'detail' => 'array'
    ];

    public function menus () {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
}