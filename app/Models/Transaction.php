<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';
    protected $keyType = 'string';
    protected $fillable = [
        'uuid',
        'xendit_id',
        'customer_id',
        'voucher_id',
        'voucher_amount',
        'shipping_address',
        'shipping_fee',
        'no_invoice',
        'delivered_type',
        'transaction_date',
        'payment_type',
        'transaction_send_date',
        'postman_name',
        'shipping_type',
        'total_price',
        'city',
        'note',
        'payment_code_sistem',
        'channel',
        'time_send',
        'channel_code',
        'payment_detail',
        'status',
        'va_expired_at',
    ];

    public static $withoutAppends = false;

    // protected $hidden = ['payment_detail'];
    protected $appends = ['is_first_order','status_transaction','jam_pengiriman','quantity','total_belanja','total_discount_voucher','status_int','payment_preferences'];

    protected static function bootUuid()
    {
        static::creating(function ($model) {
            $model->uuid = Uuid::generate()->string;
        });
    }

    public function getIsFirstOrderAttribute()
    {
        $countOrderCustomer = Customers::where('id', $this->costumer_id)->whereHas('transaction')->count();
        if ($countOrderCustomer == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function getJamPengirimanAttribute()
    {
        if ($this->time_send == '1' ) {
            return 'Pagi, 06:00 - 10:00';
        } else if ($this->time_send == '2') {
            return 'Sore, 14:00 - 16:00';
        } else {
            return null;
        }
    }

    public function getPaymentPreferencesAttribute()
    {
        return json_decode($this->payment_detail);

    }


    public function getStatusIntAttribute()
    {
        return (int)$this->status;
    }
    
    protected function getArrayableAppends()
    {
        if (self::$withoutAppends) {
            return [
                'payment_preferences',
                'is_first_order',
            ];
        }

        return parent::getArrayableAppends();
    }
    
    public function getTotalDiscountVoucherAttribute()
    {
        if ($this->voucher_id != null) {

            $voucher = $this->voucher()->first();

            if($voucher) {
                if ($voucher->discount_type == 'fixed') {
                    return $this->discount_value;
                } else {
                    $totalShopping = $this->getTotalBelanjaAttribute();
                    $discountValue = $voucher->discount_value / 100;
        
                    $discount = $totalShopping * $discountValue;
        
                    if ($discount < $voucher->discount_maximal) {
                        $totalDiscount = $totalShopping + $this->shipping_fee - $discount;
                        return $totalDiscount;
                    } else {
                        $totalDiscount = $totalShopping + $this->shipping_fee - $voucher->discount_maximal;
                        return $totalDiscount;
                    }
        
                }
            }
    
        }

        return 0;
    }

    public function getTotalBelanjaAttribute()
    {
        return $this->item()->sum('subtotal');
    }

    public function getQuantityAttribute()
    {
        return $this->item()->sum('qty');
    }

    public function getItemCountAttribute()
    {
        return $this->item()->count();
    }

    public function getStatusTransactionAttribute()
    {
        $status = $this->status;
        if ($status == '0') {
            if ($this->payment_type == 'sistem') {
                $data = 'Menunggu Pembayaran';
            } else {
                $data = 'Menunggu Pembayaran Dikonfirmasi';
            }
        } else if ($status == '1') {
            $data = 'Pembayaran COD';
        } else if ($status == '2') {
            $data = 'Menunggu Konfirmasi';
        } else if ($status == '3') {
            $data = 'Pesanan Diproses';
        } else if ($status == '4') {
            $data = 'Pesanan Dikirim';
        } else if ($status == '5') {
            $data = 'Pesanan Selesai';
        } else if ($status == '6') {
            $data = 'Pesanan Dibatalkan';
        } else {
            $data = $this->status;
        }

        return $data;
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'id');
    }

   public function item()
   {
        return $this->hasMany(TransactionProduct::class, 'transaction_id', 'id');
   }

   public function firstitem()
   {
        return $this->hasOne(TransactionProduct::class, 'transaction_id', 'id')->oldest();
   }

   public function product()
   {
        return $this->hasMany(TransactionProduct::class, 'transaction_id', 'id');  
   }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
    }

    public function receiver()
    {
        return $this->hasMany(TransactionReceiver::class, 'transaction_id', 'id');
    }

    public function history()
    {
        return $this->hasMany(TrStatusHistory::class, 'transaction_id', 'id');
    }
}
