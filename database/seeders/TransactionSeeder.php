<?php

namespace Database\Seeders;

use App\Models\Customers;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionProduct;
use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {

            $customer = Customers::first();
            $voucher = Voucher::where('discount_type','fixed')->first();
            $product = Product::first();
            $id = [
                '1' => Uuid::generate()->string,
                '2' => Uuid::generate()->string,
                '3' => Uuid::generate()->string,
                '4' => Uuid::generate()->string,
            ];

            $transaction = [
                [
                    'id'                => $id['1'],
                    'customer_id'       => $customer->id,
                    'shipping_address'  => 'JL Ampera Raya GG KANCIL',
                    'shipping_fee'      => 20000,
                    'city'              => 'Jakarta',
                    'no_invoice'        => 'HF3434',
                    'transaction_date'  => Date::now(),
                    'payment_type'      => 'sistem',
                    'shipping_type'     => 'delivered',
                    'total_price'       => 320000,
                    'note'              => 'Note',
                    'status'            => '2',
                    'created_at'        => Date::now(),
                    'updated_at'        => Date::now(),
                ],
                [
                    'id'                => $id['2'],
                    'customer_id'       => $customer->id,
                    'shipping_address'  => 'JL Ampera Raya GG KANCIL 1',
                    'shipping_fee'      => 20000,
                    'city'              => 'Jakarta',
                    'no_invoice'        => 'HF3469',
                    'transaction_date'  => Date::now(),
                    'payment_type'      => 'cod',
                    'shipping_type'     => 'delivered',
                    'total_price'       => 150000,
                    'note'              => 'Note',
                    'status'            => '1',
                    'created_at'        => Date::now(),
                    'updated_at'        => Date::now(),
                ],
                [
                    'id'                => $id['3'],
                    'customer_id'       => $customer->id,
                    'voucher_id'        => $voucher->id,
                    'shipping_address'  => 'JL Ampera Raya GG KANCIL 1',
                    'shipping_fee'      => 20000,
                    'city'              => 'Jakarta',
                    'no_invoice'        => 'HF3468',
                    'transaction_date'  => Date::now(),
                    'payment_type'      => 'sistem',
                    'shipping_type'     => 'delivered',
                    'total_price'       => 138000,
                    'note'              => 'Note',
                    'status'            => '0',
                    'created_at'        => Date::now(),
                    'updated_at'        => Date::now(),
                ],
            ];
            
            foreach ($transaction as $data) {
                Transaction::create($data);
            }

            $dataItem = [
                [
                    'transaction_id'    => $id['1'],
                    'product_id'        => $product->id,
                    'qty'               => 3,
                    'item_price'        => $product->price,
                    'discount_price'    => $product->promo_price,
                    'subtotal'          => ($product->price * 3) - $product->promo_price,
                ],
                [
                    'transaction_id'    => $id['2'],
                    'product_id'        => $product->id,
                    'qty'               => 2,
                    'item_price'        => $product->price,
                    'discount_price'    => $product->promo_price,
                    'subtotal'          => ($product->price * 2) - $product->promo_price,
                ],
                [
                    'transaction_id'    => $id['3'],
                    'product_id'        => $product->id,
                    'qty'               => 1,
                    'item_price'        => $product->price,
                    'discount_price'    => $product->promo_price,
                    'subtotal'          => ($product->price * 1) - $product->promo_price,
                ]
            ];

            foreach ($dataItem as $item) {
                TransactionProduct::create($item);
            }
            DB::commit();
        } catch (\Exception $err) {
            DB::rollBack();
            dd($err->getMessage().' - '.$err->getLine());
        }
    }
}
