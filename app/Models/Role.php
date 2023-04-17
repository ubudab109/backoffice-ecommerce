<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    
    protected $table = 'role';
    protected $keyType = 'string';
    protected $fillable = ['id','title','status','creator_id'];

    public function creator()
    {
        return $this->belongsTo(Users::class, 'creator_id', 'id');
    }
}
