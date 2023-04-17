<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseData($draw, $countData, array $listData = [])
    {
        if (count($listData) < 1 || $listData == null) {
            return array(
                "draw"              => $draw,
                "recordsTotal"      => 0,
                "recordsFiltered"   => 0,
                "data"              => []
            );
        } else {
            return array(
                "draw"              => $draw,
                "recordsTotal"      => $countData,
                "recordsFiltered"   => $countData,
                "data"              => $listData
            );
        }
    }
}
