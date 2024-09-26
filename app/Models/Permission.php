<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'canonical',
        'created_at',
        'updated_at',
    ];

    public function user_catalogues () {
        return $this->belongsToMany(UserCatalogue::class, 'user_catalogue_permission')->withPivot(['permission_id', 'user_catalogue_id']);
    }
}