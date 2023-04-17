<?php

namespace App\Http\Controllers\Api\Xendit;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Xendit\Xendit;

use function PHPUnit\Framework\returnSelf;

class XenditPaymentController extends Controller
{
    private $callbackToken;
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('app.xendit_secret_key');
        $this->callbackToken = config('app.xendit_callback_token');    
    }

    /**
     * Callback Ewallet
     * 
     */
    public function eWalletCallback()
    {
        $reqHeaders = getallheaders();
        $xIncomingCallbackTokenHeader = isset($reqHeaders['X-Callback-Token']) ? $reqHeaders['X-Callback-Token'] : "";

        if ($xIncomingCallbackTokenHeader == $this->callbackToken) {
            $rawRequestInput = file_get_contents("php://input");
            $arrRequestInput = json_decode($rawRequestInput, true);
            DB::beginTransaction();
            try {
                $data = Transaction::where('uuid', $arrRequestInput['data']['reference_id'])->first();
                if (!$data) {
                    return response()->json('Transaction Not Found', 404);
                }
                if ($arrRequestInput['data']['status'] == 'PENDING') {
                    $data->update([
                        'status' => '0'
                    ]);
                } else if ($arrRequestInput['data']['status'] == 'FAILED') {
                    $data->update([
                        'status' => '6',
                    ]);
                } else if ($arrRequestInput['data']['status'] == 'SUCCEEDED') {
                    $data->update([
                        'status' => '1',
                    ]);
                } else {
                    throw new Exception("Ewallet has a different status with system");
                }
                
                DB::commit();
                return response()->json($arrRequestInput, 200);
            } catch (\Exception $err) {
                DB::rollBack();
                return response()->json('Internal Server Error', 500);
            }
        } else {
            throw new Exception("Callback Need Callback Token");
        }
    }

    /**
     * VA Callback
     * 
     */
    public function virtualAccountCallback(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->headers->set('X-CALLBACK-TOKEN', $this->callbackToken);
            $getVa = Transaction::where('uuid', $request->external_id)->exists();
            if (!$getVa) {
                return response()->json('Transaction Not Found', 404);
            }

            $updateVa = Transaction::where('uuid', $request->external_id)->first();
            $expiredVa = strtotime($updateVa->va_expired_at);
            $now = strtotime(Date::now());

            if ($expiredVa < $now) {
                return response()->json('Transaction expired', 422);
            }

            $updateVa->update([
                'status' => '1'
            ]);
            DB::commit();
            return response()->json('Transaction paid successfully', 200);
        } catch (\Exception $err) {
            DB::rollBack();
            return response()->json('Internal Server Error', 500);
        }
    }

    /**
     * Retail Callback
     * 
     */
    public function retailCallback(Request $request)
    {
        DB::beginTransaction();
        try {
            Xendit::setApiKey($this->apiKey);
            $request->headers->set('X-CALLBACK-TOKEN', $this->callbackToken);
            $isTransactionExists = Transaction::where('uuid', $request->external_id);

            if (!$isTransactionExists->exists()) {
                return response()->json('Transaction Not Found', 422);
            }

            $transaction = $isTransactionExists->first();
            $transaction->update([
                'status' => '1'
            ]);
            DB::commit();
            return response()->json('Transaction Paid Successfully');
        } catch (\Exception $err) {
            DB::rollBack();
            return response()->json('Internal Server Error', 500);
        }
    }

}
/* Used to verify the callback from Xendit. */
