<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    /**
     * Products data
     */
    public function index($categoryId)
    {
        $data = [];
        $data['category'] = CategoryProduct::find($categoryId)->name;
        $data['products'] = Product::select('id','name','description','product_price','promo_price','promo_status','category_id')->where('category_id', $categoryId)->where('status','1')->get()->makeHidden(['promo_price','promo_status']);

        if (count($data['products']) < 1) {
            return $this->sendNotFound('Produk');
        }

        return $this->sendResponse($data);
    }

    /**
     * Products all data with offset and limit
     */
    public function all(Request $request, $categoryId)
    {
        $data = [];
        $data['category'] = $categoryId == 'promo' ? 'Promo' : CategoryProduct::find($categoryId)->name;
        $data['offset'] = $request->has('offset') && $request->offset != null ? $request->offset : 0;
        $data['limit'] = $request->has('limit') && $request->limit != null ? $request->limit : 10;
        $data['products'] = Product::select('id','name','description','product_price','promo_price','promo_status','category_id')
                                ->where('status','1')
                                ->offset($data['offset'])
                                ->limit($data['limit'])
                                ->when($categoryId, function($query) use ($categoryId) {
                                    if($categoryId == 'promo'){
                                        return $query->where('promo_status','1');
                                    }else {
                                        return $query->where('category_id', $categoryId);
                                    }
                                })
                                ->get()
                                ->makeHidden(['promo_price','promo_status']);

        if (count($data['products']) < 1) {
            return $this->sendNotFound('Produk');
        }

        return $this->sendResponse($data);
    }

    /**
     * Detail product
     */
    public function detail($productId)
    {
        $product = Product::find($productId);
        if ($product == null) {
            return $this->sendNotFound('Produk');
        }
        return $this->sendResponse($product);
    }

    /**
     * Search product
     */
    public function search(Request $request)
    {
        $data = [];
        $offset = $request->has('offset') && $request->offset != null ? $request->offset : 0;
        $limit = $request->has('limit') && $request->limit != null ? $request->limit : 6;
        $data['keywords'] = $request->keywords;
        $data['offset'] = $request->offset;
        $data['limit'] = $request->limit;
        $data['products'] = Product::when($request->has('keywords') && $request->keywords != '', function ($query) use ($request) {
            $query->where('name','LIKE','%'.$request->keywords.'%')
                ->orWhere('description', 'LIKE', '%'.$request->keywords.'%');
        })
        ->offset($offset)->limit($limit)->where('status','1')->get()->makeHidden(['promo_price','promo_status']);

        if (count($data['products']) < 1) {
            return $this->sendNotFound('Produk');
        }

        return $this->sendResponse($data);
    }

    public function getBulk(Request $request) {
        $data = [];
        $productIds = $request->ids;

        if(empty($productIds)) {
            return $this->sendBadRequest("Invalid Parameter",["Array of product id are required"]);
        }

        $data['ids'] = $productIds;
        $data['products'] = Product::whereIn('id', $productIds)
                            ->where('status','1')
                            ->get()
                            ->map(function($product) {
                                $item = [
                                    "id" => $product->id,
                                    "name" => $product->name,
                                    "discount_percent" => $product->discount_percent,
                                    "discount_price" => $product->discount_price,
                                    "real_price" => $product->real_price,
                                    "price" => $product->price,
                                    "stock" => $product->stock,
                                ];
        
                                return $item;
                            });
        
        if(sizeof($data['products']) == 0) {
            return $this->sendNotFound('Product List');
        }

        return $this->sendResponse($data);
    }
}
