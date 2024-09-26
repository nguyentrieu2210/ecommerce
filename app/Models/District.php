<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    public function wards () {
        return $this->hasMany(Ward::class, 'district_id', 'code');
    }

    public function provinces() {
        return $this->belongsTo(Province::class, 'province_id', 'code');
    }
}