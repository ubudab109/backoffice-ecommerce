<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionProduct;
use App\Models\TrStatusHistory;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\DownloadStruk;

class TransactionController extends BaseController
{
    /**
     * List transaction or Detail
     * 
     * @param Illuminate\Http\Request
     */
    public function index(Request $request)
    {
        Transaction::$withoutAppends = true;
        $transaction = Transaction::where('customer_id', Auth::guard('api')->id());

        if ($request->has('keywords') && $request->keywords != '') {
            $transaction->where('id', 'LIKE', '%' . $request->keywords . '%')
                ->orWhere('no_invoice', 'LIKE', '%' . $request->keywords . '%')
                ->orWhereHas('item.product', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->keywords . '%');
                });
        }

        if ($request->has('id') && $request->id != null) {
            $res = $transaction->select(
                'id',
                'id as id_transaksi',
                'no_invoice as invoice',
                DB::raw("DATE_FORMAT(transaction_date,'%d %M %Y') as tanggal"),
                DB::raw("DATE_FORMAT(transaction_date,'%H:%i') as jam"),
                'total_price as final_price',
                'status as status_transaksi',
                'shipping_type as jenis_pengiriman',
                'delivered_type as kurir',
                'shipping_address as alamat',
                'transaction_send_date as tanggal_pengiriman',
                'time_send as waktu_pengiriman',
                'payment_type as payment_method',
                'shipping_fee as ongkir',
                'total_price as final_price',
                'payment_detail',
            )
                ->with('product')
                ->where('id', $request->id)->first();
            if (!$res) {
                return $this->sendNotFound('transaksi');
            }

            $res->makeHidden(['id','payment_detail']);
        } else {
            $res['customer']       = Auth::guard('api')->user()->name;
            $res['transaction']    = Transaction::where('customer_id', Auth::guard('api')->id())
                ->select(
                    'id as id_transaksi',
                    'no_invoice as invoice',
                    DB::raw("DATE_FORMAT(transaction_date,'%d %M %Y') as tanggal"),
                    DB::raw("DATE_FORMAT(transaction_date,'%H:%i') as jam"),
                    'total_price as final_price',
                    'status as status_transaksi',
                    'payment_detail',
                )->get()->makeHidden(['payment_detail']);
        }

        $res['customer']       = Auth::guard('api')->user()->name;
        $res['transaction']    = Transaction::where('customer_id', Auth::guard('api')->id())
            ->select(
                'id as id_transaksi',
                'no_invoice as invoice',
                DB::raw("DATE_FORMAT(transaction_date,'%d %M %Y') as tanggal"),
                DB::raw("DATE_FORMAT(transaction_date,'%H:%i') as jam"),
                'total_price as final_price',
                'status as status_transaksi',
                'payment_detail',

            )->get()->makeHidden(['payment_detail']);

        return $this->sendResponse($res);
    }

    public function getTransaction(Request $request)
    {
        // $data['customer'] = Auth::guard('api')->user();
        $data['filter'] = $request->all();
        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 0;
        $data['filter']['limit'] = $limit;
        $data['filter']['offset'] = $offset;
        $startDate = !empty($request->start_date) ? substr($request->start_date, 0, 10) : 0;
        $endDate = !empty($request->end_date) && $request->end_date >= $request->start_date ? substr($request->end_date, 0, 10) : 0;
        $data['filter']['parsed_start'] = $startDate;
        $data['filter']['parsed_end'] = $endDate;

        $data['transaction'] = Transaction::where('customer_id', Auth::guard('api')->id())
                        ->when($request->has('keywords') && $request->keywords != '', function($query) use($request){
                            $query->where( function($query) use($request) {
                               return $query->where('id', 'LIKE', '%' . $request->keywords . '%') 
                                            ->orWhere('no_invoice', 'LIKE', '%' . $request->keywords . '%')
                                            ->orWhereHas('item.product', function ($query) use ($request) {
                                                $query->where('name', 'LIKE', '%' . $request->keywords . '%');
                                            });
                            });
                        })
                        ->when($request->has('start_date') && $startDate != 0, function($query) use ($startDate) {
                            return $query->whereDate('created_at', '>=', $startDate);
                        })
                        ->when($request->has('end_date') && $endDate != 0, function($query) use($endDate){
                            return $query->whereDate('created_at','<=', $endDate);
                        })
                        ->when($request->has('status') && $request->status != '' && $request->status != null, function($query) use($request) {
                            return $query->where('status',$request->status);
                        })
                        ->with('firstitem')
                        ->limit($limit)
                        ->offset($offset)
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
        return $this->sendResponse($data);
    }

    public function getDetail(Request $request)
    {
        if(!$request->has('id')) {
            return $this->sendBadRequest('Validation Error',['transaction id must specified'], 400);
        }

        $res = Transaction::with('customer', 'voucher', 'item', 'history', 'receiver')->find($request->id);

        return $this->sendResponse($res);
    }

    public function getTracking(Request $request)
    {
        
        if(!$request->has('id')) {
            return $this->sendBadRequest('Validation Error',['transaction id must specified'], 400);
        }

        $res = TrStatusHistory::where('transaction_id', $request->id)->get();

        return $this->sendResponse($res);
    }

    public function getTransactionItem(Request $request) 
    {
        if(!$request->has('id')) {
            return $this->sendBadRequest('Validation Error',['transaction id must specified'], 400);
        }

        /**
         * {
         *   "id": "03e95b10-0eeb-11ed-92f7-b327d88e5e48",
         *   "name": "Mayonaise maestro thousand island salad dressing  ",
         *   "description": "237 ml",
         *   "category_id": 15,
         *   "discount_percent": 100,
         *   "discount_price": 20500,
         *   "images": [
         *       "https://akomart-bo.vasdev.co.id/storage/images/product/1659063806041081900.jpg"
         *   ],
         *   "promo_tag": "Tersedia",
         *   "real_price": 20500,
         *   "price": 0,
         *   "stock": 0,
         *   "total_inventory": 0,
         *   "total_sold": 0,
         *   "amount": 1
         * }
         * 
         */

        $items = TransactionProduct::where('transaction_id', $request->id)
                        // ->join('product', 'product.id', 'transaction_product.product_id')
                        // ->select("transaction_product.*", "product.promo_price", "product.product_price")
                        ->with('product')
                        ->get()
                        ->map(function($trItem) {
                            $item = [
                                "id" => $trItem->product->id,
                                "name" => $trItem->product->name,
                                "description" => $trItem->product->description,
                                "category_id" => $trItem->product->category_id,
                                "discount_percent" => $trItem->product->discount_percent,
                                "discount_price" => $trItem->product->discount_price,
                                "images" => $trItem->images,
                                "promo_tag" => $trItem->product->promo_tag,
                                "real_price" => $trItem->product->real_price,
                                "price" => $trItem->product->price,
                                "stock" => $trItem->product->stock,
                                "total_inventory" => $trItem->product->total_inventory,
                                "total_sold" => $trItem->product->total_sold,
                                "amount" => $trItem->qty,
                                "note" => $trItem->note
                            ];
    
                            return $item;
                        });

        if(count($items) == 0) {
            return $this->sendNotFound('Transaksi');
        }

        // foreach ($items as $key => $item) {
        //     $product = Product::find($item->product_id);
        //     $item->product = $product;
        // }
        return $this->sendResponse($items);
    }

    public function downloadInvoice(Request $request)
    {
        $id = $request->id;

        $invoice = Transaction::where('id',$id)
                        ->where('customer_id',  Auth::guard('api')->id())
                        ->first();
        
        if(!$invoice) {
            $this->sendNotFound('Invoice');
        }

        $struk = new DownloadStruk;

        return $struk->download($invoice);
    }
}
