<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Users;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'voucher';
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'title',
        'description',
        'minimum_shop_price',
        'discount_type',
        'discount_value',
        'discount_maximal',
        'code',
        'kuota',
        'date_start_voucher',
        'date_end_voucher',
        'voucher_type',
        'status',
        'creator_id',
        'created_at',
        'updated_at',
    ];
    protected $appends = ['total_reedem','rest_voucher'];

    public function getRestVoucherAttribute()
    {
        return $this->kuota - $this->reedem()->count();
    }
    
    public function getTotalReedemAttribute()
    {
        return $this->reedem()->count();
    }

    public function creator()
    {
        return $this->belongsTo(Users::class, 'creator_id', 'id');
    }

    public function reedem()
    {
        return $this->hasMany(VoucherRedeem::class, 'voucher_id', 'id');
    }
    
}
