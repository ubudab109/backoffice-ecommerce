<?php

namespace App\Traits;

use Illuminate\Support\Facades\Date;

trait GenerateInvoice {

  /**
   * Generate Invoice
   */
  public function generateInvoice($numberTransaction)
  {
      $date = date('Ymd', strtotime(Date::now()));
      $transactionType = 'RT';
      $accountType = 'C00';
      return 'INV/'.$date.'/'.$transactionType.'/'.$accountType.$numberTransaction;
  }

  /**
   * Generate Transaction
   */
  public function generateTransaction($numberTransaction)
  {
      $date = date('Ymd', strtotime(Date::now()));
      $transactionType = 'RT';
      $accountType = 'C00';
      return $date.'/'.$transactionType.'/'.$accountType.$numberTransaction;
  }
}