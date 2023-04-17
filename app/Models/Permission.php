<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permission';
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'title',
        'parent_id',
        'created_at',
        'updated_at',
    ];

    public function child()
    {
        return $this->hasMany(Permission::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_id', 'id');
    }
}
