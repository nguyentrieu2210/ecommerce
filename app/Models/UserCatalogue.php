<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCatalogue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'description',
        'publish',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function users () {
        return $this->hasMany(User::class, 'user_catalogue_id', 'id');
    }

    public function permissions () {
        return $this->belongsToMany(Permission::class, 'user_catalogue_permission', 'user_catalogue_id', 'permission_id');
    }
}