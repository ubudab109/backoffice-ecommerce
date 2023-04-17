<?php

namespace App\Models;

use App\Traits\UuidGenerate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Banner extends Model
{
    use HasFactory, SoftDeletes, UuidGenerate;

    protected $table = 'banner';
    protected $keyType = 'string';
    protected $fillable = ['title', 'file', 'status'];

    protected static function bootUuid()
    {
        static::creating(function ($model) {
            $model->id = Uuid::generate()->string;
        });
    }

    public function creator()
    {
        return $this->belongsTo(Users::class, 'creator_id', 'id');
    }
}
