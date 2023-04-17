<?php

use PhpParser\Node\Expr\FuncCall;

function paymentSuccess(array $data) {
  return '
  [Jangan Dibalas]
  Pembayaran diterima - '.$data['payment_type'].'
  ID Transaksi '.$data['id_transaction'].'
  No Invoice '.$data['invoice'].'
  Detail ordermu di Akomart.

  '.$data['customer'].' '.$data['phone'].'
  '.$data['address'].'

  '.$data['no'].'. '.$data['qty'].'x '.rupiah($data['price_product']).' ('.$data['description'].')
  {('.rupiah($data['price_product']).')} = '.rupiah($data['total_product_price']).'

  Total Belanja: '.rupiah($data['total_product_price']).'
  Ongkir: '.rupiah($data['ongkir']).'
  Diskon: '.rupiah($data['discount_price']).'
  Total Tagihan: '.rupiah($data['final_price']).'

  Catatan Penjual: 
  Pembayaran: '.$data['payment_type'].'
  Pengiriman: '.$data['shipping_address'].'
  Tanggal Kirim: '.$data['transaction_send'].'
  Waktu Kirim : '.$data['time_send'].'
  Untuk info lebih lanjut, silahkan hubungi no CS wa.me/08123456789
  Terima Kasih 🙏
  ';
}

function paymentFailed(array $data) {
  return '
  [Jangan Dibalas]
  Pembayaran gagal, silahkan pesan ulang di Akomart.
  ID Transaksi '.$data['id_transaction'].'
  No Invoice '.$data['invoice'].'
  Detail ordermu di Akomart.

  '.$data['customer'].' '.$data['phone'].'
  '.$data['address'].'

  '.$data['no'].'. '.$data['qty'].'x '.rupiah($data['price_product']).' ('.$data['description'].')
  {('.rupiah($data['price_product']).')} = '.rupiah($data['total_product_price']).'

  Total Belanja: '.rupiah($data['total_product_price']).'
  Ongkir: '.rupiah($data['ongkir']).'
  Diskon: '.rupiah($data['discount_price']).'
  Total Tagihan: '.rupiah($data['final_price']).'

  Catatan Penjual: 
  Pembayaran: '.$data['payment_type'].'
  Pengiriman: '.$data['city'].'
  Tanggal Kirim: '.$data['transaction_send'].'
  Waktu Kirim : '.$data['time_send'].'
  Untuk info lebih lanjut, silahkan hubungi no CS wa.me/08123456789
  Terima Kasih 🙏
  ';
}

function paymentSuccessPickup(array $data) {
  return '
  [Jangan Dibalas]
  Pembayaran diterima - '.$data['payment_type'].'
  ID Transaksi '.$data['id_transaction'].'
  No Invoice '.$data['invoice'].'
  Detail ordermu di Akomart.

  '.$data['customer'].' '.$data['phone'].'
  '.$data['address'].'

  '.$data['no'].'. '.$data['qty'].'x '.rupiah($data['price_product']).' ('.$data['description'].')
  {('.rupiah($data['price_product']).')} = '.rupiah($data['total_product_price']).'

  Total Belanja: '.rupiah($data['total_product_price']).'
  Ongkir: '.rupiah($data['ongkir']).'
  Diskon: '.rupiah($data['discount_price']).'
  Total Tagihan: '.rupiah($data['final_price']).'

  Catatan Penjual: 
  Pembayaran: '.$data['payment_type'].'
  Pengiriman: Pickup
  Tanggal Kirim: '.$data['transaction_send'].'
  Waktu Kirim : '.$data['time_send'].'
  Untuk info lebih lanjut, silahkan hubungi no CS wa.me/08123456789
  Terima Kasih 🙏
  ';
}

function paymentSuccessPickupCOD(array $data) {
  return '
  [Jangan Dibalas]
  Pembayaran diterima - COD
  ID Transaksi '.$data['id_transaction'].'
  No Invoice '.$data['invoice'].'
  Detail ordermu di Akomart.

  '.$data['customer'].' '.$data['phone'].'
  '.$data['address'].'

  '.$data['no'].'. '.$data['qty'].'x '.rupiah($data['price_product']).' ('.$data['description'].')
  {('.rupiah($data['price_product']).')} = '.rupiah($data['total_product_price']).'

  Total Belanja: '.rupiah($data['total_product_price']).'
  Ongkir: '.rupiah($data['ongkir']).'
  Diskon: '.rupiah($data['discount_price']).'
  Total Tagihan: '.rupiah($data['final_price']).'

  Catatan Penjual: 
  Pembayaran: '.$data['payment_type'].'
  Pengiriman: Pickup
  Tanggal Kirim: '.$data['transaction_send'].'
  Waktu Kirim : '.$data['time_send'].'
  Untuk info lebih lanjut, silahkan hubungi no CS wa.me/08123456789
  Terima Kasih 🙏
  ';
}