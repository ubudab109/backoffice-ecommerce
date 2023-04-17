<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForgotPasswordToken extends Model
{
    use HasFactory;

    protected $table = 'forgot_password_token';
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'token',
        'email',
        'expired_date',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
}
