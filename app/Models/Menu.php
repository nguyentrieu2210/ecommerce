<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'keyword',
        'publish',
        'created_at',
        'updated_at',
    ];
    
    public function links () {
        return $this->hasMany(Link::class, 'menu_id', 'id');
    }
}