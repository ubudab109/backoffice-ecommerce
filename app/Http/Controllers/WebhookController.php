<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Webhook;

class WebhookController extends Controller
{
    //
    public function receive(Request $request)
    {
        $json = json_encode($request->all());

        $webhook = new Webhook();

        $webhook->request = $json;
        $webhook->save();

        return response('webhook received', 200);
    }
}
