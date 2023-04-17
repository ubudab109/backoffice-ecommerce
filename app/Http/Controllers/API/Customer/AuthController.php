<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Traits\WhatsappAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tzsk\Otp\Facades\Otp;
use Webpatser\Uuid\Uuid;

use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    use WhatsappAuth;

    /**
     * SEND OTP AND TOKEN
     */
    public function send(Request $request)
    {
        $validate = Validator::make($request->all() ,[
            'phone'     => 'required',
            'channel'   => 'required',
        ]);

        if ($validate->fails()) {
            return $this->sendBadRequest('Validation Error', $validate->errors());
        }
        
        DB::beginTransaction();
        try {
            $idNewCustomer = Uuid::generate()->string;
            $input = $request->all();
            $tokenGenerate = generateRandomString(40);
            $customer = Customers::where('whatsapp',$input['phone'])->first();
            if (!$customer) {
                $uniqueKey = $idNewCustomer;
            } else {
                $uniqueKey = $customer->id;
            }
            $otp = Otp::expiry(1)->digits(4)->generate($uniqueKey);
            if ($input['channel'] == 'wa') {
                // $otpSend = $this->sendWa($input['phone'], $otp.' adalah kode verifikasi dari Akomart. JANGAN MEMBERITAHU KODE RAHASIA INI KE SIAPAPUN termasuk pihak yang mengaku dari Akomart.');
                $otpSend = $this->sendWaQiscus($input['phone'], [$otp]);
                $through = 'whatsapp';
                
            } else if ($input['channel'] == 'sms') {
                $otpSend = $this->sendSms($input['phone'], $otp.' adalah kode verifikasi dari Akomart. JANGAN MEMBERITAHU KODE RAHASIA INI KE SIAPAPUN termasuk pihak yang mengaku dari Akomart.');
                $through = 'sms';
            }
            $resOtpSend = json_decode($otpSend);
            
            if ($resOtpSend->result != 'true') {
                return $this->sendError('failed',[
                    'otp gagal dikirim', $resOtpSend->message
                ]);
            }
            if (!$customer) {
                Customers::create([
                    'id'       => $idNewCustomer,
                    'whatsapp' => $input['phone']
                ]);
            } else {

                $customer->update([
                    'token' => $tokenGenerate,
                    'otp'   => $otp,
                ]);
            }
            
            $res = [
                'token' => $tokenGenerate,
            ];
            DB::commit();
            return $this->sendResponse($res, [
                'OTP berhasil dikirim melalui '.$through.' ke '.$input['phone'],
                $resOtpSend->message
            ]);
        } catch (\Exception $err) {
            DB::rollBack();
            return $this->sendError('failed',[
                $err->getMessage().$err->getLine()
            ]);
        }
    }

    /**
     * Verify TOKEN AND OTP 
     */
    public function verify(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'otp'       => 'required',
            'token'     => 'required',
        ]);

        if ($validate->fails()) {
            return $this->sendBadRequest('Validator Error', $validate->errors());
        }

        $customer = Customers::where('token', $request->token)->first();
        if (!$customer) {
            return $this->sendNotFound('Customer');
        }

        DB::beginTransaction();
        try {

            if ($request->otp != $customer->otp) {
                return $this->sendError('failed',[
                    'Verifikasi Gagal'
                ]);
            }

            $res['token'] = $customer->createToken('AKOmart Customer')->accessToken;
            $res['customer'] = $customer;
            $messages = 'Verfikasi OTP Berhasil';

            $customer->update([
                'token' => null,
                'otp'   => null,
            ]);

            DB::commit();
            return $this->sendResponse($res, [
                $messages
            ]);

        } catch (\Exception $err) {
            DB::rollBack();
            return $this->sendError('Validation', $err->getMessage());
        }

    }

    public function refresh(Request $request) {
        $id = Auth::guard('api')->id();
        $customer = Customers::find($id);
        return $this->sendResponse($customer);
    }
}
