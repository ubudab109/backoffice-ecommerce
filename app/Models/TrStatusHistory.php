<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'tr_status_history';
    protected $fillable = [
        'transaction_id',
        'trx_status_id',
        'trx_status_text',
        'status_notes',
    ];

}
