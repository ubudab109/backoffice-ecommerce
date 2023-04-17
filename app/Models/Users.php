<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model
{
    use SoftDeletes;
    protected $table = 'users';
    protected $keyType = 'string';
    protected $appends = ['role_name'];

    protected $fillable = [
        'id',
        'creator_id',
        'email',
        'password',
        'status',
        'address',
        'type',
        'phone_number',
        'token',
        'attempts',
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected $hidden = ['password'];

    public function userAdmin()
    {
        return $this->hasOne(UserAdmin::class, 'user_id', 'id');
    }
    
    public function getRoleNameAttribute()
    {
        return $this->userAdmin()->first()->role()->first()->title;
    }

    public function creator()
    {
        return $this->belongsTo(Users::class, 'creator_id', 'id');
    }
}
