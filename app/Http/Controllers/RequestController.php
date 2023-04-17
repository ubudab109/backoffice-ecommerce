<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Req;
use GuzzleHttp\Client;
use Exception;
use Session;
use CURLFile;

class RequestController extends Controller
{
	private $client;
	private $headers;

	public function __construct()
	{

		$this->client = new Client([
			'base_uri'=>env('API_LINK')
		]);

        $this->asset = new Client([
            'base_uri'=>env('CDN_PUBLIC_URL')
        ]);
	}

    public function sendRequest($module, $request)
    {
    	$headers = [
            'Content-Type' => 'application/json',
            'api-key' => env('API_KEY')
    	];
        $request = $this->client->post('api/v1',[
    		'headers' => $headers,
            'json' =>[
                "module" => $module,
    			"data"=>$request
            ],
            'verify' => false
        ]);
        $response = json_decode($request->getBody()->getContents(),true);
        return $response;
    }

    public function sendRequestFiel($param)
    {
        $request = $this->client->post('api/v1',[
            'headers' => [
                'api-key' => env('API_KEY')
            ],
            'multipart' => $param,
            'verify' => false
        ]);
        $response = json_decode($request->getBody()->getContents(),true);
        return $response;
    }

}
