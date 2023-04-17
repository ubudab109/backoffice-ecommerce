<?php

namespace App\Services;

use Carbon\Carbon;
use Xendit\Xendit;

class XenditServices {

  public function __construct()
  {
    $apiKey = config('app.xendit_secret_key');
    Xendit::setApiKey($apiKey);
  }


  public function PaymentChannels()
  {
    return \Xendit\PaymentChannels::list();
  }

  public function CreateRetailPayment(array $data)
  {
    $param = [
      'external_id'           => $data['externalId'],
      'retail_outlet_name'    => $data['channelCode'],
      'name'                  => $data['customerName'],
      'expected_amount'       => $data['amount'],
      'expiration_date'       => Carbon::now()->addHours(12),
      'is_single_use'         => true,
    ];

    return \Xendit\Retail::create($param);
  }

  public function CreateFixedVA(array $data)
  {
    $param = [
      'external_id'     => $data['externalId'],
      'bank_code'       => $data['channelCode'],
      'name'            => $data['customerName'],
      'is_closed'       => true,
      'is_single_use'   => true,
      'expected_amount' => $data['amount'],
      'expiration_date' => Carbon::now()->addHours(24),
    ];

    return \Xendit\VirtualAccounts::create($param);
  }

  /**
   * Process crete payment with ewallet
   */
  public function CreateChargesEWallet(array $data)
  {
    
    if ($data['channelCode'] == 'ID_OVO') {
      $channelProperties = [
        'mobile_number' => $data['phoneNumber'],
        'success_redirect_url'  => 'http://localhost:8000/test',
        'failure_redirect_url'  => 'http://localhost:8000/test'
      ];
    } else {
      $channelProperties = [
        'success_redirect_url'  => 'http://localhost:8000/test',
        'failure_redirect_url'  => 'http://localhost:8000/test',
      ];
    }

    $param = [
      'reference_id'        => $data['referenceId'],
      'currency'            => 'IDR',
      'channel_code'        => $data['channelCode'],
      'amount'              => $data['amount'],
      'checkout_method'     => 'ONE_TIME_PAYMENT',
      'channel_properties'  => $channelProperties
    ];

    return \Xendit\EWallets::createEWalletCharge($param);
  }

  /**
   * Get ewallet payment status
   */
  public function EWalletStatus($charge_id)
  {
    $getEWalletChargeStatus = \Xendit\EWallets::getEWalletChargeStatus($charge_id);
    return $getEWalletChargeStatus;
  }

  public function createInvoce(array $invoiceDetail)
  {
    $invoiceDetail["currency"] = "IDR";
    $invoiceDetail["success_redirect_url"] = env("INV_SUCCESS_REDIRECT",'https://akomart.id/transaksi').'?id='.$invoiceDetail['external_id'];
    $invoiceDetail["failure_redirect_url"] = env("INV_FAILED_REDIRECT",'https://akomart.id/transaksi').'?id='.$invoiceDetail['external_id'];

    $createInvoice = \Xendit\Invoice::create($invoiceDetail);

    return $createInvoice;
  }


}