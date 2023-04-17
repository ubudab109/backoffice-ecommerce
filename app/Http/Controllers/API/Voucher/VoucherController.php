<?php

namespace App\Http\Controllers\Api\Voucher;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends BaseController
{

  public function index(Request $request) {
    if(!$request->has('code')) {
      return $this->sendBadRequest('Validation Error',['code must specified'], 400);
    }
    if(!$request->has('total_price')) {
      return $this->sendBadRequest('Validation Error', ['total_price must specified'], 400);
    }

    $voucher = Voucher::where('code',$request->code)->first();

    if($voucher) {
      if($voucher->status != 1) {
        return $this->sendError('unavailable', ['Voucher sudah tidak aktif'], 406);
      } else if($voucher->rest_voucher <= 0) {
        return $this->sendError('unavailable', ['Kuota voucher sudah habis'], 406);
      } else if($voucher->minimum_shop_price > $request->total_price) {
        return $this->sendError('unavailable', ['Nominal pembelian kurang dari Rp'.number_format($voucher->minimum_shop_price)], 406);
      }
      return $this->sendResponse($voucher);
    }

    return $this->sendNotFound('Voucher');
  }
}
