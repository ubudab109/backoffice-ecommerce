<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $table = 'customer_address';
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'is_default',
        'title',
        'address',
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'id');
    }
}
