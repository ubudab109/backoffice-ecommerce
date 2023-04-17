<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductFiles extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'files_product';
    protected $fillable = ['product_id','files'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
