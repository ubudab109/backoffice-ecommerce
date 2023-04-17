<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Customers extends Authenticatable
{
    use HasFactory, SoftDeletes, HasApiTokens;

    protected $table = 'customers';
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'whatsapp',
        'name',
        'token',
        'otp',
    ];
    protected $appends = [
        'can_cod'
    ];

    public function address()
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id', 'id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getCanCodAttribute()
    {
        return $this->transaction()->where('status','5')->count() > 0;
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'customer_id', 'id');
    }
}
