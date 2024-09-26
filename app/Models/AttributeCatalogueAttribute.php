<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeCatalogueAttribute extends Model
{
    use HasFactory;

    protected $table = 'attribute_catalogue_attribute';

    protected $fillable = [
        'attribute_id',
        'attribute_catalogue_id',
    ];
}