<?php
// namespace App\Helpers;

use Illuminate\Support\Facades\Request;

function queryColumn()
{
  $dataSend = array(
    "search"     => Request::input('search')['value'],
    "offset"     => Request::input('start'),
    "limit"      => Request::input('length'),
    'order'      => (!empty(Request::get('columns')[Request::get('order')[0]['column']]['data'])) ? Request::get('columns')[Request::get('order')[0]['column']]['data'] : '',
    'sort'       => (!empty(Request::get('order')[0]['dir'])) ? Request::get('order')[0]['dir'] : '',

  );

  return $dataSend;
}

if (!function_exists('canAccess')) {
  function canAccess($permissionName)
  {
    $canAccess = in_array($permissionName, session('session_id.permission'));
    return $canAccess;
  }
}

function storeImages($path, $files) 
{    

  if(is_array($files)){
    $imgs = [];
    foreach($files as $file){
      $extension = $file['file']->getClientOriginalExtension();
      $imageName = generateImageName($extension);
      $file['file']->storeAs(
          $path, $imageName
      );

      array_push($imgs, $imageName);
    }
    return $imgs;
  }else{
    $extension = $files->getClientOriginalExtension();
    $imageName = generateImageName($extension);
    $files->storeAs(
        $path, $imageName
    );
    return $imageName;
  }
}

function generateImageName($extension) {
  return preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime()).'.'.$extension;
}

function rupiah($angka){
	
	$hasil_rupiah = "Rp " . number_format($angka,0,',','.');
	return $hasil_rupiah;
 
}

function rupiahFormat($angka){
	
	$hasil_rupiah = number_format($angka,0,',','.');
	return $hasil_rupiah;
 
}

function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}


function transactionStatusBadget($uuid, $input = null) {

  // 0: menunggu pembayaran, 1:Pembayaran COD, 2:Menunggu Konfirmasi, 3:Pesanan Diproses, 4:Pesanan Dikirim, 5:Pesanan Selesai, 6:Pesanan Dibatalkan
  $data = [
    '<a href="'.route('get.transaction.detail', $uuid).'"><span style="width:180px;" class="badge badge-yellow">Menunggu Pembayaran</span></a>',
    '<a href="'.route('get.transaction.detail', $uuid).'"><span style="width:180px;" class="badge badge-yellow">Pembayaran COD</span></a>',
    '<a href="'.route('get.transaction.detail', $uuid).'"><span style="width:180px;" class="badge badge-yellow">Menunggu Konfirmasi</span></a>',
    '<a href="'.route('get.transaction.detail', $uuid).'"><span style="width:180px;" class="badge badge-yellow">Proses</span></a>',
    '<a href="'.route('get.transaction.detail', $uuid).'"><span style="width:180px;" class="badge badge-yellow">Dikirim</span></a>',
    '<a href="'.route('get.transaction.detail', $uuid).'"><span style="width:180px;" class="badge badge-green">Berhasil</span></a>',
    '<a href="'.route('get.transaction.detail', $uuid).'"><span style="width:180px;" class="badge badge-red">Dibatalkan</span></a>',
  ];

  if ($input != null) {
    return $data[$input];
  }

  return $data;
}
