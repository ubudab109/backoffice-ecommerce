<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product';
    protected $fillable = [
        'creator_id',
        'code_product',
        'name',
        'category_id',
        'description',
        'status',
        'promo_value',
        'promo_price',
        'promo_status',
        'promo_type',
        'product_price',
    ];
    protected $keyType = 'string';
    protected $hidden = ['product_price'];
    protected $appends = [
        'discount_percent', 
        'discount_price',
        'images',
        'promo_tag', 
        'real_price',
        'price',
        'stock',
        'total_inventory',
        'total_sold'
    ];

    public function getTotalSoldAttribute()
    {
        return $this->transaction()->sum('qty');
    }

    public function getTotalInventoryAttribute()
    {
        return $this->inventory()->sum('qty');
    }

    public function getStockAttribute()
    {
        $inventory = $this->inventory()->first();
        if ($inventory) {
            $totalStock = $inventory->qty - $inventory->getTotalSoldAttribute();
    
            return $totalStock;
        }

        return 0;
    }

    public function getPriceAttribute()
    {
        if ($this->promo_status == '1') {
            return $this->promo_price;
        } else {
            return $this->product_price;
        }
    }

    public function getRealPriceAttribute()
    {
        return $this->product_price;
    }

    public function getDiscountPercentAttribute()
    {
        if ($this->promo_status == '1') {
            $x = $this->product_price;
            if($x == 0) {
                // price can't be zero to apply promo
                return null;
            }
            $y = $this->promo_price;
            $n = $x - $y;
            $decimal = $n / $x;
            $percent = $decimal * 100;  
            return floor($percent);
        }
        return null;
    }

    public function getDiscountPriceAttribute()
    {
        if ($this->promo_status == '1') {
            $x = $this->product_price;
            $y = $this->promo_price;
            $n = $x - $y;
            return $n;
        }
    }

    public function getImagesAttribute()
    {
        $data = [];
        foreach ($this->files()->orderBy('id','ASC')->get() as $file) {
            array_push($data, $file->files);
        }

        return $data;
    }
    
    public function getPromoTagAttribute()
    {
        $tag = $this->tag()->first();
        if ($tag) {
            return $this->tag()->first()->tag_name;
        }

        return null;
    }

    public function files()
    {
        return $this->hasMany(ProductFiles::class, 'product_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(CategoryProduct::class, 'category_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(Users::class, 'creator_id', 'id');
    }

    public function inventory()
    {
        return $this->hasMany(ProductInventory::class, 'product_id', 'id');
    }

    public function tag()
    {
        return $this->hasOne(TagProduct::class, 'product_id', 'id');
    }

    public function transaction()
    {
        return $this->hasMany(TransactionProduct::class, 'product_id', 'id');
    }
}
