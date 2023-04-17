<?php

namespace App\Services;

use Dompdf\Dompdf;
use Intervention\Image\Facades\Image;

class DownloadStruk {


  /**
   * Download transaction detail as pdf
   * @param Transaction invoice 
   */
  public function download($invoice)
  {

    $totalPayment = $invoice->shipping_fee + $invoice->total_price - ($invoice->voucher_amount ? $invoice->voucher_amount : 0);

    setlocale(LC_TIME, 'id_ID');
    // dd($invoice, $invoice->receiver()->first(), $invoice->customer()->first());
    $image = Image::make(public_path('image/logo-akomart.png'))->encode('data-url');
    
    $view = view('transaction.invoice', ["invoice" => $invoice, "total_payment" => number_format($totalPayment, 0, ',', '.'), 'image' => $image]);

    $name = 'INVOICE-'.str_replace('/','-',$invoice->id);

    $dompdf = new Dompdf(['enable_remote' => false]);

    $options = $dompdf->getOptions();
    $options->setDefaultFont('Arial');
    $dompdf->setOptions($options);
    $dompdf->load_html($view); 
    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();

    return $dompdf->stream($name);
  }
}
