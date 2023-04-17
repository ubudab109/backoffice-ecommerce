<?php

namespace App\Traits;

/**
    !!!! PLEASE DO NOT RE-FORMAT (TABULATION) RETURN STRING IN THE METHODS SEND MESSAGE AFTER sendWA FUNCTION (FUNCTION sendMessage UNTIL transactionSend). THIS FORMAT CAN'T BE CHANGE
    !!!! IF YOU CHANGE THIS FORMAT, THE MESSAGE WAS SEND TO WHATSAPP WILL BE BROKEN
**/
trait MessageAdminToCustomer {

  private function forDataProduct(array $product) 
  {
    $break = urlencode("\n");
    $res = '';
    foreach ($product as $key => $data) {
      $res .= "".$key + 1 .". ".$data['quantity']." x ".rupiah($data['price_product'])."(".$data['description'].")
      {(".rupiah($data['price_product']).")} = ".rupiah($data['total_product_price'])."".$break;
    }

    return $res;
  }
  
  public function sendSms($phone, $message)
  {
    $accesskey = config('app.wa_access_key');
    $phone = $phone; //atau bisa menggunakan 62812xxxxxxx
    $message = $message;

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://app.ruangwa.id/api/send_sms',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => 'accesskey=' . $accesskey . '&number=' . $phone . '&message=' . $message,
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }

  public function sendWa($phone, $message) {
    $url = config('app.pejer_url');
    $headers = [
      'Authorization' => 'Bearer '.config('app.pejer_token'),
    ];

    $body = [
      "to" => $phone,
      "body" => $message,
      "type" => "chat"
    ];

    $request = Http::withHeaders($headers)->post($url, $body);

    return json_encode(["result"=>true, "message" => "Pesan sedang dikirim"]);
    
  }

  public function xenditTransactionCreated(array $data)
  {
    $message = '
> Pembayaran Checkout Dibuat
Transaksi Anda dengan AKOMart telah dibuat.

Referensi ID: '.$data['id_transaction'].'
Amount: '.$data['final_price'].'
Deskripsi: Invoice for Akomart Transaction

Untuk menyelesaikan transaksi ini, silakan buka tautan di bawah ini, https://dev.xen.to/WX1NnHFb dan bayar sebelum 12 Aug 2022, 11:18 (GMT+07:00).

Untuk informasi lebih lanjut silahkan hubungi admin kami di (link wa admin)
    ';
  }
}