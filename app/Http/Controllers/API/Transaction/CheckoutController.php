<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionProduct;
use App\Models\TransactionReceiver;
use App\Models\Users;
use App\Models\Voucher;
use App\Models\VoucherRedeem;
use App\Models\TrStatusHistory;
use App\Services\XenditServices;
use App\Traits\GenerateInvoice;
use App\Traits\WhatsappAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Webpatser\Uuid\Uuid;


class CheckoutController extends BaseController
{
    use GenerateInvoice, WhatsappAuth;

    /**
     * It returns a list of payment channels.
     */
    public function listPaymentChannels()
    {
        $xendit = new XenditServices();

        return $xendit->PaymentChannels();
    }

    /**
     * The above function is a function to make a transaction.
     * 
     * @param Request request The request object.
     * 
     * @return The response is a JSON object.
     */
    public function checkout(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'items'                                     => 'required|array',
            'customer.nama_customer'                    => 'required',
            'customer.no_whatsapp_customer'             => 'required',
            'customer.alamat_customer'                  => 'required_if:is_dropship,==,false',
            'is_dropship'                               => 'required',
            'dropship_detail.nama_penerima'             => 'required_if:is_dropship,==,true',
            'dropship_detail.no_whatsapp_penerima'      => 'required_if:is_dropship,==,true',
            'dropship_detail.alamat_penerima'           => 'required_if:is_dropship,==,true',
            'metode_pembayaran'                         => 'required',
            'channel'                                   => 'required_if:metode_pembayaran,==,sistem',
            'channel_code'                              => 'required_if:metode_pembayaran,==,sistem',
            'phone_number'                              => 'required_if:channel,==,EWALLET',
        ]);

        if ($validate->fails()) {
            return $this->sendBadRequest('Validator Error', $validate->errors());
        }
        $input = $request->all();

        $customer = Customers::where('whatsapp', $input['customer']['no_whatsapp_customer'])->first();
        if (!$customer) {
            return $this->sendBadRequest('failed','Customer tidak ditemukan');
        }

        if($customer->name == null) {
            $customer->update([
                'name' => $input['customer']['nama_customer']
            ]);
        }

        $totalDataTransaction = Transaction::count();
        $idTransaction = $this->generateTransaction($totalDataTransaction + 1);
        $invoiceTransaction = $this->generateInvoice($totalDataTransaction + 1);
        $tmpTotalPrice = [];

        // FOR NOTIF WA CUSTOMER
        $dataNotifCustomer = [
            'customer'          => $customer->name,
            'phone'             => $customer->whatsapp,
            'id_transaction'    => $idTransaction,
            'invoice'           => $invoiceTransaction,
            'address'           => $input['customer']['alamat_customer'],
            'product'           => [],
        ];

        // FOR NOTIF WA ADMIN
        $dataNotifAdmin = [
            'customer'          => $customer->name,
            'phone'             => $customer->whatsapp,
            'id_transaction'    => $idTransaction,
            'invoice'           => $invoiceTransaction,
            'address'           => $input['customer']['alamat_customer'],
            'product'           => [],
        ];
        DB::beginTransaction();
        try {
            $uuid = Uuid::generate()->string;
            // Transaction
            $transaction = new Transaction;
            $transaction->id = $idTransaction;
            $transaction->uuid = $uuid;
            $transaction->customer_id = $customer->id;
            $transaction->no_invoice = $invoiceTransaction;
            
            $transaction->shipping_address = $input['customer']['alamat_customer'];
            if ($input['metode_pembayaran'] == 'sistem' || $input['metode_pembayaran'] == 'invoice') {
                $dataNotifCustomer['payment_type'] = $input['channel_code']; // currently only statis
                $dataNotifAdmin['payment_type'] = $input['channel_code']; // currently only statis
                $transaction->payment_type = 'sistem';
                $transaction->status = '0';
            } else if ( strtolower($input['metode_pembayaran']) == 'cod') {
                $transaction->payment_type = 'cod';
                $transaction->status = '1';
                $dataNotifCustomer['payment_type'] = 'COD'; // currently only statis
                $dataNotifAdmin['payment_type'] = 'COD'; // currently only statis
            } else if ($input['metode_pembayaran'] == 'manual') {
                $transaction->payment_type = 'manual';
                $transaction->status = '0';
                $dataNotifCustomer['payment_type'] = 'manual'; // currently only statis
                $dataNotifAdmin['payment_type'] = 'manual'; // currently only statis
            }
            else {
                $dataNotifCustomer['payment_type'] = $input['channel_code']; // currently only statis
                $dataNotifAdmin['payment_type'] = $input['channel_code']; // currently only statis
                $transaction->payment_type =  'manual';
                $transaction->status = '0';
            }
            $transaction->shipping_type = $input['pengiriman']['jenis_pengiriman'];
            
            $transaction->transaction_send_date = $input['pengiriman']['tanggal_pengiriman'];
            $transaction->transaction_date = Date::now();
            $transaction->time_send = $input['pengiriman']['waktu_pengiriman'];
            $dateSend = date('Y-m-d',strtotime($input['pengiriman']['tanggal_pengiriman']));
            $dataNotifCustomer['transaction_send'] = $dateSend;
            $dataNotifAdmin['transaction_send'] = $dateSend;
            if ($input['pengiriman']['waktu_pengiriman'] == '1') {
                $dataNotifCustomer['time_send'] = 'Pagi, 06:00 - 10:00';
                $dataNotifAdmin['time_send'] = 'Pagi, 06:00 - 10:00';
            } else if ($input['pengiriman']['waktu_pengiriman'] == '2') {
                $dataNotifCustomer['time_send'] = 'Sore';
                $dataNotifAdmin['time_send'] = 'spre';
            }
            
            $transaction->note = $input['pengiriman']['note_pengiriman'];

            $deliveryFee = $input['pengiriman']['jenis_pengiriman'] == 'delivered' ? 10000 : 0;
            $transaction->shipping_fee = $deliveryFee;
            $dataNotifCustomer['ongkir'] = $deliveryFee;
            $dataNotifAdmin['ongkir'] = $deliveryFee;
            $transaction->save();

            // DROPSHIP DETAIL IF TRUE
            if ($input['is_dropship'] == true) {
                $receiver = new TransactionReceiver;
                $receiver->transaction_id = $idTransaction;
                $receiver->receiver_name = $input['dropship_detail']['nama_penerima'];
                $receiver->receiver_whatsapp = $input['dropship_detail']['no_whatsapp_penerima'];
                $receiver->receiver_address = $input['dropship_detail']['alamat_penerima'];
            }
            // $dataNotifCustomer['product'] = [];
            // $dataNotifAdmin['product'] = [];
            // INSERT ITEM
            foreach ($input['items'] as $index => $itemInput) {
                $product = Product::find($itemInput['id']);
                $transactionItem = new TransactionProduct;
                $transactionItem->transaction_id = $idTransaction;
                $transactionItem->product_id = $itemInput['id'];
                $transactionItem->qty = $itemInput['quantity'];
                $transactionItem->item_price = $product->product_price;
                $transactionItem->discount_price = $product->promo_price;
                // $transactionItem->subtotal = ($product->price * $itemInput['quantity']) - $product->promo_price;
                // array_push($tmpTotalPrice, ($product->price * $itemInput['quantity']) - $product->promo_price);
                $transactionItem->subtotal =  $product->price * $itemInput['quantity'];
                array_push($tmpTotalPrice, $transactionItem->subtotal);

                // DATA PRODUCT NOTIF
                $arrProduct = [
                    'quantity'              => $itemInput['quantity'],
                    'price_product'         => $product->price,
                    'nama'                  => $product->name,
                    'description'           => strip_tags($product->description),
                    'total_product_price'   => $itemInput['quantity'] * $product->price,
                ];
                $dataNotifCustomer['product'][] = $arrProduct;
                $dataNotifAdmin['product'][] = $arrProduct;
                $transactionItem->note = $itemInput['note'];
                $transactionItem->save();
            }
            
            $transactionUpdate = Transaction::find($idTransaction);

            $updateTransaction = [];
            $voucherAmount = 0;
            if (isset($input['id_voucher']) && $input['id_voucher'] != null) {
                $voucher = Voucher::find($input['id_voucher']);
                if($voucher && $voucher->status == 1 && $voucher->rest_voucher > 0) {
                    if ($voucher->discount_type == 'discount') {
                        $totalPriceProduct = array_sum($tmpTotalPrice);
                        $discount = $voucher->discount_value / 100;
                        $deducted = $totalPriceProduct * $discount;
                        $deducted = $deducted > $voucher->discount_maximal ? $voucher->discount_maximal : $deducted;
                        $dataNotifCustomer['discount_price'] = $deducted;
                        $dataNotifAdmin['discount_price'] = $deducted;
                        $discountPriceNotif = $deducted;
                    } else if ($voucher->discount_type == 'fixed') {
                        $dataNotifCustomer['discount_price'] = $voucher->discount_value;
                        $dataNotifAdmin['discount_price'] = $voucher->discount_value;
                        $discountPriceNotif =  $voucher->discount_value;
                    }
                    $updateTransaction['voucher_amount'] = $discountPriceNotif;
                    $voucherAmount = $discountPriceNotif;
                    $updateTransaction['voucher_id'] = $voucher->id;
                    $newRedeem = new VoucherRedeem();
                    $newRedeem->voucher_id = $voucher->id;
                    $newRedeem->save();
                }
            } else {
                $dataNotifCustomer['discount_price'] = 0;
                $dataNotifAdmin['discount_price'] = 0;
                $discountPriceNotif = 0;
            }
            
            
            if ($input['metode_pembayaran'] == 'sistem') {
                // logic for payment gateway (not implemented yet);
                $xendit = new XenditServices();
                // VIRTUAL_ACCOUNT, RETAIL_OUTLET, EWALLET, QRIS
                if ($input['channel'] == 'VIRTUAL_ACCOUNT') {
                    $dataVaPaymentXendit = [
                        'externalId'           => $uuid,
                        'channelCode'          => $input['channel_code'],
                        'customerName'         => $input['customer']['nama_customer'],
                        'amount'               => array_sum($tmpTotalPrice),
                    ];
                    $xenditPayment = $xendit->CreateFixedVA($dataVaPaymentXendit);
                } else if ($input['channel'] == 'RETAIL_OUTLET') {
                    $dataRetailPaymentXendit = [
                        'externalId'           => $uuid,
                        'channelCode'          => $input['channel_code'],
                        'customerName'         => $customer->name,
                        'amount'               => array_sum($tmpTotalPrice),
                    ];
                    $xenditPayment = $xendit->CreateRetailPayment($dataRetailPaymentXendit);
                } else if ($input['channel'] == 'EWALLET') {
                    $dataEwalletPaymentXendit = [
                        'referenceId'           => $uuid,
                        'channelCode'           => 'ID_'.$input['channel_code'],
                        'phoneNumber'           => $input['phone_number'],
                        'amount'                => array_sum($tmpTotalPrice),
                    ];
                    if (isset($input['phone_number'])) {
                        $dataEwalletPaymentXendit['phoneNumber'] = $input['phone_number'];
                    }
                    $xenditPayment = $xendit->CreateChargesEWallet($dataEwalletPaymentXendit);

                } 
                // NOT IMPLEMENTED YET
                // else if ($input['channel'] == 'QRIS') {

                // }
                else {
                    return response()->json('Channel Pembayaran Tidak Ditemukan',400);
                }

                $updateTransaction['total_price'] = array_sum($tmpTotalPrice);
                
                if ($input['channel'] == 'VIRTUAL_ACCOUNT') {
                    $updateTransaction['payment_code_sistem'] = $xenditPayment['account_number'];
                    $updateTransaction['va_expired_at'] = $xenditPayment['expiration_date'];
                } else if ($input['channel'] == 'RETAIL_OUTLET') {
                    $updateTransaction['payment_code_sistem'] = $xenditPayment['payment_code'];
                } else if ($input['channel'] == 'EWALLET') {
                    if (isset($xenditPayment['actions']['qr_checkout_string'])) {
                        $updateTransaction['payment_code_sistem'] = $xenditPayment['actions']['qr_checkout_string'];
                    }
                }

                $updateTransaction['xendit_id'] = $xenditPayment['id'];
                $updateTransaction['channel_code'] = $input['channel_code'];
                $updateTransaction['channel'] = $input['channel'];
                $updateTransaction['payment_detail'] = $xenditPayment;
                

                // $transactionUpdate->update($updateTransaction);
            } else if ($input['metode_pembayaran'] == 'invoice') {
                $xendit = new XenditServices();
                $invoiceAmount = array_sum($tmpTotalPrice) + $transaction->shipping_fee;
                $invoiceDetail = [
                    'external_id' => $idTransaction,
                    'description' => 'Invoice for Akomart transaction',
                    'customer' => [
                        'given_name' => $customer->name,
                        'mobile_number' => $customer->whatsapp
                    ],
                    'items' => array_map(function($arr) {
                        return [
                            'name' => $arr['nama'],
                            'price' => $arr['price_product'],
                            'quantity' => $arr['quantity']
                        ];
                    }, $dataNotifCustomer['product'])
                ];

                array_push($invoiceDetail['items'],['name' => 'Ongkos Kirim', 'price' => $transaction->shipping_fee, 'quantity' => 1]);
                // If thera are any voucher applied
                if(isset($voucher)) {
                    $invoiceAmount = $invoiceAmount - $discountPriceNotif;
                    array_push($invoiceDetail['items'],['name' => 'DISKON VOUCHER: IDR '.number_format($discountPriceNotif,0,',','.'), 'price' => 0, 'quantity' => 1]);
                }
                $invoiceDetail['amount'] = $invoiceAmount;
                $xenditInvoice = $xendit->createInvoce($invoiceDetail);
                
                // $dataNotifCustomer['invoice_url'] = $xenditInvoice['invoice_url'];
                $dataNotifCustomer['payment_type'] = $xenditInvoice['invoice_url'];
                $dataNotifAdmin['payment_type'] =  $xenditInvoice['invoice_url'];

                $updateTransaction['xendit_id'] = $xenditInvoice['id'];
                $updateTransaction['payment_code_sistem'] = $xenditInvoice['invoice_url'];
                $updateTransaction['total_price'] = array_sum($tmpTotalPrice);
                // $transactionUpdate->update($updateTransaction);
                
            } else {
                $updateTransaction['payment_code_sistem'] =  $input['metode_pembayaran'];
                $updateTransaction['total_price'] = array_sum($tmpTotalPrice);
            }
            
            // CALCULE TOTAL SHOPING FOR MESASAGE
            $dataNotifCustomer['total_shopping'] = array_sum($tmpTotalPrice);
            $dataNotifAdmin['total_shopping'] = array_sum($tmpTotalPrice);
            
            
            // CHECK FOR DELIVERED TYPE PICKUP OR DELIVERED
            
            // CALCULATE FINAL PRICE FOR MESSAGE
            $dataNotifCustomer['final_price'] = array_sum($tmpTotalPrice) + $deliveryFee - $discountPriceNotif;
            $dataNotifAdmin['final_price'] = array_sum($tmpTotalPrice) + $deliveryFee - $discountPriceNotif;
            if ($input['pengiriman']['jenis_pengiriman'] == 'pickup') {
                $dataNotifCustomer['delivered_type'] = 'Pickup';
                $dataNotifAdmin['delivered_type'] = 'Pickup';
                // CHECK PAYMENT TYPE COD OR SYSTEM (PAYMENT GATEWAY) THIS CHECKING IS FOR CUSTOMER NOTIF
                if ($input['metode_pembayaran'] == 'cod') {
                    $messageCustomer = $this->checkoutSuccessPickupCod($dataNotifCustomer);
                }  else {
                    $messageCustomer = $this->checkoutSuccessPickup($dataNotifCustomer);
                }
            } else if ($input['pengiriman']['jenis_pengiriman'] == 'delivered') {
                $dataNotifCustomer['delivered_type'] = 'Surabaya';
                $dataNotifAdmin['delivered_type'] = 'Surabaya';
                $messageCustomer = $this->checkoutSuccess($dataNotifCustomer);
            }
            
            
            // MESSAGE FOR ADMIN
            if ($input['metode_pembayaran'] == 'cod') {
                $messageAdmin = $this->paymentCheckoutCODAdmin($dataNotifAdmin);
            }  else {
                $messageAdmin = $this->paymentCheckoutSystemAdmin($dataNotifAdmin);
            }
            
            // RESPONSE
            $res = [
                'id_transaksi'      => $idTransaction,
                'invoice'           => $invoiceTransaction,               
                'payment_code' => $input['metode_pembayaran'] == 'sistem' ? $xenditPayment['id'] : null,
                'invoice_url' => $input['metode_pembayaran'] == 'invoice' ? $xenditInvoice['invoice_url'] : null,
                'payment_method' => $input['metode_pembayaran'],
                'applied_voucher' => isset($voucher) ? $voucher->title: 'none',
                'voucher_amount' => $voucherAmount,
                'delivery_type' => $input['pengiriman']['jenis_pengiriman'],
                'delivery_fee' => $deliveryFee,
                'final_price'       => $dataNotifCustomer['final_price'],
            ];
            
            $metodePembayaran = strtolower($input['metode_pembayaran']);
            if ($metodePembayaran == 'sistem') {
                $res['payment_detail'] = $xenditPayment;
            } else if ($metodePembayaran == 'transfer bca') {
                $rekDesc = config('app.rek_bca');
                $res['payment_detail'] = 'Transfer ke BANK BCA: '.$rekDesc.' sejumlah IDR '.number_format($dataNotifCustomer['final_price'],0,',','.');
            } else if ($metodePembayaran == 'transfer mandiri') {
                $rekDesc = config('app.rek_mandiri');
                $res['payment_detail'] = 'Transfer ke bank Mandiri: '.$rekDesc.' sejumlah IDR '.number_format($dataNotifCustomer['final_price'],0,',','.');
            } else if ($metodePembayaran == 'invoice') {
                $res['payment_detail'] = $xenditInvoice['invoice_url'];
            } else {
                $res['payment_detail'] = 'Serahkan uang sejumlah IDR '.number_format($dataNotifCustomer['final_price'],0,',','.').' kepada Kurir saat menerima barang';
            }
            $updateTransaction['payment_detail'] = json_encode([
                'type' => $metodePembayaran,
                'detail' => $res['payment_detail']
            ]);
            $transactionUpdate->update($updateTransaction);
            // SENDING MESSAGE WA TO ALL ADMIN THAT ACTIVE
            $this->sendWa($customer->whatsapp, $messageCustomer);
            foreach (Users::where('status','1')->get() as $admin) {
                $this->sendWa($admin->phone_number, $messageAdmin, true);
            }
            $this->insertHistory($input, $transaction, $idTransaction);

            DB::commit();
            return $this->sendResponse($res, [
                'checkout berhasil'
            ]);
        } catch (\Exception $err) {
            return $err;
            DB::rollBack();
            return $this->sendError('Error',$err->getMessage()  );
        }
    }

    private function insertHistory(array $input, Transaction $transaction, String $trId) {
        $newHistory = new TrStatusHistory();
        $newHistory->transaction_id = $trId;
        $newHistory->trx_status_id = $transaction->status;
        $newHistory->trx_status_text = $transaction->status_transaction;
        $newHistory->status_notes = json_encode($input);
        $newHistory->save();        
    }

    public function invoiceCallback(Request $request)
    {
        $callbackToken = $request->header('x-callback-token');
        $validToken = config('app.callback_token');

        if($callbackToken != $validToken) {
            return $this->sendBadRequest('Invalid Token',['Invalid callback token'], 401);
        }
        
        DB::beginTransaction();
        $transaction = Transaction::find($request->external_id);

        if(!$transaction) {
            return $this->sendNotFound('Transaksi');
        }
        try {
            if($request->status == 'PAID') {
                $statusId = 2;
                $statusText = 'Menunggu Konfirmasi';
            } else {
                $statusId = 6;
                $statusText = 'Pesanan Dibatalkan - Invoice Expired';
            }
            //code...
            $statusHistory = new TrStatusHistory();
            $statusHistory->transaction_id = $transaction->id;
            $statusHistory->trx_status_id = $statusId;
            $statusHistory->trx_status_text = $statusText;
            $statusHistory->status_notes = json_encode($request->all());
            $statusHistory->save();

            $transaction->update([
                'status' => ''.$statusId
            ]);

            DB::commit();
            return $this->sendResponse(['created_at' => $statusHistory->created_at],'callback received');
        } catch (\Exception $err) {
            //throw $th;
            return $err;
            DB::rollBack();
        }
    }

}
