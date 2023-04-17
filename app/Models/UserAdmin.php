<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAdmin extends Model
{
    protected $table = 'user_admin';
    protected $keyType = 'string';

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
