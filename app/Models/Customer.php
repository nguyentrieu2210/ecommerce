<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'province_id',
        'district_id',
        'ward_id',
        'birthday',
        'image',
        'address',
        'description',
        'password',
        'publish',
        'created_at',
        'updated_at',
        'customer_catalogue_id',
        'source_id',
        'deleted_at',
        'remember_token',
        'email_verifield_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function customer_catalogues () {
        return $this->belongsTo(CustomerCatalogue::class, 'customer_catalogue_id', 'id');
    }

    public function sources () {
        return $this->belongsTo(Source::class, 'source_id', 'id');
    }
}
