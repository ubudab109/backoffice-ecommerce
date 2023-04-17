<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'category_product';
    protected $keyType = 'integer';
    protected $fillable = ['name','code_category','status','creator_id','icon'];

    public function creator()
    {
        return $this->belongsTo(Users::class, 'creator_id', 'id');
    }
}
