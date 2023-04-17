<?php

namespace App\Http\Controllers\API\Category;

use App\Http\Controllers\API\BaseController;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function index(Request $request)
    {
        $keywords = $request->keywords;
        $data['offset'] = $request->has('offset') && $request->offset != null ? $request->offset : 0;
        $data['limit'] = $request->has('limit') && $request->limit != null ? $request->limit : 100;

        $category = CategoryProduct::select('id','name','icon')
        ->where('status','1')
        ->when($request->has('keywords') && $request->keywords != '', function ($query) use ($keywords) {
            $query->where('name', 'like','%'.$keywords.'%');
        })
        ->offset($data['offset'])
        ->limit($data['limit'])
        ->get();
        
        if (count($category) < 1) {
            return $this->sendNotFound('Category');
        } 
        return $this->sendResponse($category);
    }
}
