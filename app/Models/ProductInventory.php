<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductInventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_inventory';
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'product_id',
        'qty',
        'creator_id',
        'note',
    ];

    protected $appends = ['total_sold'];

    public function getTotalSoldAttribute()
    {
        $transactionProduct = TransactionProduct::where('product_id', $this->product_id)->sum('qty');
        return $transactionProduct;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(Users::class, 'creator_id', 'id');
    }
}
