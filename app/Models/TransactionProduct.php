<?php

namespace App\Models;

use App\Traits\UuidGenerate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionProduct extends Model
{
    use HasFactory, UuidGenerate;

    protected $table = 'transaction_product';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'transaction_id',
        'product_id',
        'qty',
        'item_price',
        'discount_price',
        'subtotal',
        'note'
    ];

    protected $appends = ['name', 'images','price', 'product_code'];

    public function getPriceAttribute()
    {
        if ($this->discount_price != 0) {
            return $this->discount_price;
        } else {
            return $this->item_price;
        }
    }

    public function getNameAttribute()
    {
        
        return $this->product()->first() ? $this->product()->first()->name : 'Produk Sudah Tidak Tersedia';
    }

    public function getImagesAttribute()
    {
        return $this->product()->first() ? $this->product()->first()->files()->pluck('files') : 'Produk Sudah Tidak Tersedia';
    }

    public function getProductCodeAttribute()
    {
        return $this->product()->first() ? $this->product()->first()->code_product : 'Produk Sudah Tidak Tersedia';
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
