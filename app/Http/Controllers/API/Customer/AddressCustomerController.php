<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;

class AddressCustomerController extends BaseController
{

    /**
     * List Address Customer
     */
    public function index()
    {
        $userId = Auth::guard('api')->id();
        $customer = Customers::find($userId);
        $adress = $customer->address()
        ->select('id','title as judul_alamat','address as alamat','is_default as is_utama')
        ->get();
        if (count($adress) > 0) {
            $data['customer'] = $customer->name;
            $data['alamat'] = $adress;
            return $this->sendResponse($data, null);
        }
        return $this->sendNotFound('Alamat', 404);
    }

    /**
     * Add Customer Address
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'judul_alamat'  => 'required',
            'alamat'        => 'required',
            'is_utama'      => 'required',
        ]);

        if ($validate->fails()) {
            return $this->sendBadRequest('Validation Error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $custId = Auth::guard('api')->id();
            $input = $request->all();
            $customer = Customers::find($custId);
            if (!$customer) {
                return $this->sendNotFound('customer');
            }
            $countAddress = $customer->address()->count();
            if ($countAddress < 5) {

                if ($input['is_utama'] == 1 || $input['is_utama'] == '1') {
                    // IF PREVIOUSLY HAS A DEFAULT ADDRESS, THEN CHANGE DATA IS DEFAULT
                    $addressDefault = CustomerAddress::where([
                        'customer_id' => $custId,
                        'is_default'  => 1,
                    ])->first();

                    if ($addressDefault) {
                        $addressDefault->update([
                            'is_default' => 0
                        ]);
                    }
                    
                    $isDefault = 1;
                } else {
                    $isDefault = 0;
                }

                $address = $customer->address()->create([
                    'id'            => Uuid::generate()->string,
                    'is_default'    => $isDefault,
                    'title'         => $input['judul_alamat'],
                    'address'       => $input['alamat'],
                ]);

                DB::commit();
                return $this->sendResponse($address, [
                    'alamat berhasil ditambah',
                ]);
            }

            return $this->sendBadRequest('failed','jumlah alamat melebihi batas');
        } catch (\Exception $err) {
            DB::rollBack();
            return $this->sendError('failed',[
                'gagal menambah alamat'
            ]);
        }
    }


    /**
     * Update Address Customer
     */
    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id'            => 'required',
            'judul_alamat'  => 'required',
            'alamat'        => 'required',
            'is_utama'      => 'required',
        ]);

        if ($validate->fails()) {
            return $this->sendBadRequest('Validation Error', $validate->errors());
        }
        
        DB::beginTransaction();
        try {
            $input = $request->all();
            $address = CustomerAddress::find($input['id']);
            if ($input['is_utama'] == 1 || $input['is_utama'] == '1') {
                if ($address->is_utama == 0) {
                    // IF PREVIOUSLY HAS A DEFAULT ADDRESS, THEN CHANGE DATA IS DEFAULT
                    $addressDefault = CustomerAddress::where([
                        'customer_id' => $address->customer_id,
                        'is_default'  => 1,
                    ])->first();
                    
                    if ($addressDefault) {
                        $addressDefault->update([
                            'is_default' => 0
                        ]);
                    }
                    $isDefault = 1;
                } else {
                    $isDefault = 0;
                }
            }
            $address->update([
                'is_default'    => $isDefault ,
                'title'         => $input['judul_alamat'],
                'address'       => $input['alamat'],
            ]);
            DB::commit();
            return $this->sendResponse($address, [
                'alamat berhasil diperbarui',
            ]);
        } catch (\Exception $err) {
            DB::rollBack();
            return $this->sendError('failed',[
                $err->getMessage(),
                $err->getLine()
            ]);
        }

        // return $this->sendResponse();


    }
}
