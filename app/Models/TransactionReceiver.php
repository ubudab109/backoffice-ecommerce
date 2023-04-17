<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionReceiver extends Model
{
    use HasFactory;

    protected $table = 'transaction_receiver';
    protected $fillable = [
        'customer_id',
        'transaction_id',
        'receiver_name',
        'receiver_whatsapp',
        'receiver_address',
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
