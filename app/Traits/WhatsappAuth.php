<?php

namespace App\Traits;
use Illuminate\Support\Facades\Http;



/**
    !!!! PLEASE DO NOT RE-FORMAT (TABULATION) RETURN STRING IN THE METHODS SEND MESSAGE AFTER sendWA FUNCTION (FUNCTION sendMessage UNTIL transactionSend). THIS FORMAT CAN'T BE CHANGE
    !!!! IF YOU CHANGE THIS FORMAT, THE MESSAGE WAS SEND TO WHATSAPP WILL BE BROKEN
**/

trait WhatsappAuth
{
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

  /**
   * Send notification via whatsapp
   * @param string $phone Receiver phone number
   * @param string $messsge Message to send
   * @param boolean $async Option to send request asychronously
   */

  public function sendWaOld($phone, $message, $async = false)
  {
    $token = config('app.wa_token');
    $phone = $phone; //untuk group pakai groupid contoh: 62812xxxxxx-xxxxx
    $message = $message;

    if($async) {
      $url = 'https://app.ruangwa.id/api/send_message';
      $data = ['token' => $token, 'number' => $phone, 'message' => $message];
      $response = Http::async()->asForm()->post($url, $data)->then(function ($response) {
            echo "Response received!";
            echo $response->body();
        });
      
      return 'message request send';
    }

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => 'token=' . $token . '&number=' . $phone . '&message=' . $message,
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

  public function checkoutSuccess(array $data)
  {
    $message = '
[Jangan Dibalas]
Pemesanan Berhasil
Lakukan Pembayaran - '.$data['payment_type'].'
ID Transaksi '.$data['id_transaction'].'
No Invoice '.$data['invoice'].'
Detail ordermu di Akomart.

'.$data['customer'].' '.$data['phone'].'
'.$data['address'].'

'.$this->forDataProduct($data['product']).'

Total Belanja: '.rupiah($data['total_shopping']).'
Ongkir: '.rupiah($data['ongkir']).'
Diskon: '.rupiah($data['discount_price']).'
Total Tagihan: '.rupiah($data['final_price']).'

Catatan Penjual: 
Pembayaran: '.$data['payment_type'].'
Pengiriman: Surabaya / Sidoarjo (Waru - Gedangan)
Tanggal Kirim: '.$data['transaction_send'].'
Waktu Kirim : '.$data['time_send'].'
Untuk info lebih lanjut, silahkan hubungi no CS wa.me/'.env('CS_WA').' 
Terima Kasih 🙏
    ';

    return $message;
  }

  public function checkoutSuccessPickup(array $data)
  {
    $pembayaran = isset($data['invoice_url']) ? $data['invoice_url'] : $data['payment_type'];
    $message = '
[Jangan Dibalas]
Pemesanan Berhasil
Lakukan Pembayaran - '.$data['payment_type'].'
ID Transaksi '.$data['id_transaction'].'
No Invoice '.$data['invoice'].'
Detail ordermu di Akomart.

'.$data['customer'].' '.$data['phone'].'
'.$data['address'].'

'.$this->forDataProduct($data['product']).'

Total Belanja: '.rupiah($data['total_shopping']).'
Ongkir: '.rupiah($data['ongkir']).'
Diskon: '.rupiah($data['discount_price']).'
Total Tagihan: '.rupiah($data['final_price']).'

Catatan Penjual: 
Pembayaran: '.$pembayaran.'
Pengiriman: Pickup
Tanggal Kirim: '.$data['transaction_send'].'
Waktu Kirim : '.$data['time_send'].'
Untuk info lebih lanjut, silahkan hubungi no CS wa.me/'.env('CS_WA').' 
Terima Kasih 🙏
          ';

    return $message;
  }

  public function checkoutSuccessPickupCod(array $data)
  {
    return '
[Jangan Dibalas]
Pemesanan Berhasil
Lakukan Pembayaran - COD
ID Transaksi '.$data['id_transaction'].'
No Invoice '.$data['invoice'].'
Detail ordermu di Akomart.

'.$data['customer'].' '.$data['phone'].'
'.$data['address'].'

'.$this->forDataProduct($data['product']).'

Total Belanja: '.rupiah($data['total_shopping']).'
Ongkir: '.rupiah($data['ongkir']).'
Diskon: '.rupiah($data['discount_price']).'
Total Tagihan: '.rupiah($data['final_price']).'

Catatan Penjual: 
Pembayaran: '.$data['payment_type'].'
Pengiriman: Pickup
Tanggal Kirim: '.$data['transaction_send'].'
Waktu Kirim : '.$data['time_send'].'
Untuk info lebih lanjut, silahkan hubungi no CS wa.me/'.env('CS_WA').' 
Terima Kasih 🙏
    ';
  }
  

  public function paymentSuccess(array $data) 
  {
    
    return '
[Jangan Dibalas]
Pembayaran diterima - '.$data['payment_type'].'
ID Transaksi '.$data['id_transaction'].'
No Invoice '.$data['invoice'].'
Detail ordermu di Akomart.

'.$data['customer'].' '.$data['phone'].'
'.$data['address'].'

'.$this->forDataProduct($data['product']).'

Total Belanja: '.rupiah($data['total_shopping']).'
Ongkir: '.rupiah($data['ongkir']).'
Diskon: '.rupiah($data['discount_price']).'
Total Tagihan: '.rupiah($data['final_price']).'

Catatan Penjual: 
Pembayaran: '.$data['payment_type'].'
Pengiriman: Surabaya
Tanggal Kirim: '.$data['transaction_send'].'
Waktu Kirim : '.$data['time_send'].'
Untuk info lebih lanjut, silahkan hubungi no CS wa.me/'.env('CS_WA').' 
Terima Kasih 🙏
    ';
  }
  
  public function paymentFailed(array $data) 
  {
    return '
[Jangan Dibalas]
Pembayaran gagal, silahkan pesan ulang di Akomart.
ID Transaksi '.$data['id_transaction'].'
No Invoice '.$data['invoice'].'
Detail ordermu di Akomart.

'.$data['customer'].' '.$data['phone'].'
'.$data['address'].'

'.$this->forDataProduct($data['product']).'

Total Belanja: '.rupiah($data['total_shopping']).'
Ongkir: '.rupiah($data['ongkir']).'
Diskon: '.rupiah($data['discount_price']).'
Total Tagihan: '.rupiah($data['final_price']).'

Catatan Penjual: 
Pembayaran: '.$data['payment_type'].'
Pengiriman: '.$data['delivered_type'].'
Tanggal Kirim: '.$data['transaction_send'].'
Waktu Kirim : '.$data['time_send'].'
Untuk info lebih lanjut, silahkan hubungi no CS wa.me/'.env('CS_WA').' 
Terima Kasih 🙏
    ';
  }
  
  public function paymentSuccessPickup(array $data) 
  {
    $pembayaran = isset($data['invoice_url']) ? $data['invoice_url'] : $data['payment_type'];
    $message = '
[Jangan Dibalas]
Pembayaran diterima - '.$data['payment_type'].'
ID Transaksi '.$data['id_transaction'].'
No Invoice '.$data['invoice'].'
Detail ordermu di Akomart.

'.$data['customer'].' '.$data['phone'].'
'.$data['address'].'

'.$this->forDataProduct($data['product']).'

Total Belanja: '.rupiah($data['total_shopping']).'
Ongkir: '.rupiah($data['ongkir']).'
Diskon: '.rupiah($data['discount_price']).'
Total Tagihan: '.rupiah($data['final_price']).'

Catatan Penjual: 
Pembayaran: '.$pembayaran.'
Pengiriman: Pickup
Tanggal Kirim: '.$data['transaction_send'].'
Waktu Kirim : '.$data['time_send'].'
Untuk info lebih lanjut, silahkan hubungi no CS wa.me/'.env('CS_WA').' 
Terima Kasih 🙏
    ';

    return $message;
  }
  
  public function paymentSuccessPickupCOD(array $data) 
  {
    return '
[Jangan Dibalas]
Pembayaran diterima - COD
ID Transaksi '.$data['id_transaction'].'
No Invoice '.$data['invoice'].'
Detail ordermu di Akomart.

'.$data['customer'].' '.$data['phone'].'
'.$data['address'].'

'.$this->forDataProduct($data['product']).'

Total Belanja: '.rupiah($data['total_shopping']).'
Ongkir: '.rupiah($data['ongkir']).'
Diskon: '.rupiah($data['discount_price']).'
Total Tagihan: '.rupiah($data['final_price']).'

Catatan Penjual: 
Pembayaran: '.$data['payment_type'].'
Pengiriman: Pickup
Tanggal Kirim: '.$data['transaction_send'].'
Waktu Kirim : '.$data['time_send'].'
Untuk info lebih lanjut, silahkan hubungi no CS wa.me/'.env('CS_WA').' 
Terima Kasih 🙏
    ';
  }

  public function paymentCheckoutSystemAdmin(array $data) 
  {
    return '
[Untuk Admin]
Pesanan Baru
Jenis Pembayaran - '.$data['payment_type'].'
ID Transaksi '.$data['id_transaction'].'
No Invoice '.$data['invoice'].'
Detail ordermu di Akomart.

'.$data['customer'].' '.$data['phone'].'
'.$data['address'].'

'.$this->forDataProduct($data['product']).'

Total Belanja: '.rupiah($data['total_shopping']).'
Ongkir: '.rupiah($data['ongkir']).'
Diskon: '.rupiah($data['discount_price']).'
Total Tagihan: '.rupiah($data['final_price']).'

Catatan Penjual: 
Pembayaran: '.$data['payment_type'].'
Pengiriman: '.$data['delivered_type'].'
Tanggal Kirim: '.$data['transaction_send'].'
Waktu Kirim : '.$data['time_send'].'


Mohon cek & konfirmasi status pesanan
ID Transaksi : '.$data['id_transaction'].'
No Invoice : '.$data['invoice'].'
';
  }

  public function paymentCheckoutCODAdmin(array $data) 
  {
    return '
[Untuk Admin]
Pesanan Baru
Jenis Pembayaran - '.$data['payment_type'].'
ID Transaksi '.$data['id_transaction'].'
No Invoice '.$data['invoice'].'
Detail ordermu di Akomart.

'.$data['customer'].' '.$data['phone'].'
'.$data['address'].'

'.$this->forDataProduct($data['product']).'

Total Belanja: '.rupiah($data['total_shopping']).'
Ongkir: '.rupiah($data['ongkir']).'
Diskon: '.rupiah($data['discount_price']).'
Total Tagihan: '.rupiah($data['final_price']).'

Catatan Penjual: 
Pembayaran: '.$data['payment_type'].'
Pengiriman: '.$data['delivered_type'].'
Tanggal Kirim: '.$data['transaction_send'].'
Waktu Kirim : '.$data['time_send'].'


Mohon cek & konfirmasi status pesanan
ID Transaksi : '.$data['id_transaction'].'
No Invoice : '.$data['invoice'].'
    ';
  }

  public function transactionSend(array $data)
  {
    return '
Hi Pelanggan, kurir Akomart sudah berangkat.

ID Transaksi : '.$data['id_transaction'].'
No Invoice : '.$data['invoice'].'

Apabila pada hari ini kamu belum terima, bisa hubungi CS Akomart wa.me/'.env('CS_WA').' 
*pesan ini otomatis terkirim, tidak perlu dibalas.
    ';
  }


  
}
