<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'tax_number',
        'fax',
        'website',
        'province_id',
        'district_id',
        'ward_id',
        'address',
        'publish',
        'created_at',
        'updated_at',
        'user_id',
        'deleted_at',
    ];

    public function users () {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function receiveInventories () {
        return $this->hasMany(ReceiveInventory::class, 'supplier_id', 'id');
    }
}
