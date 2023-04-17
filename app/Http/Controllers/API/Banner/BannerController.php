<?php

namespace App\Http\Controllers\API\Banner;

use App\Http\Controllers\API\BaseController;
use App\Models\Banner;

class BannerController extends BaseController
{
    /**
     * GET ALL BANNERS
     */
    public function index()
    {
        $banners = Banner::select('id','file as image_url','title as name')->where('status','1')->get();
        if (count($banners) < 1) {
            return $this->sendNotFound('Banner');
        } 
        return $this->sendResponse($banners);

    }
}
