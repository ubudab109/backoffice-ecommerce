<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherRedeem extends Model
{
    use HasFactory;

    protected $table = 'voucher_reedem';
    protected $fillable = [
        'voucher_id',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
    }
}
