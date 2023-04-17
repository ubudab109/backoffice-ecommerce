<?php

namespace Database\Seeders;

use App\Models\CustomerAddress;
use App\Models\Customers;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $customerId = Uuid::generate()->string;
            $customer = [
                'id'            => $customerId,
                'name'          => 'Customer 1',
                'whatsapp'      => '085887028342',
                'created_at'    => Date::now(),
                'updated_at'    => Date::now(),
            ];

            $customer = Customers::create($customer);

            $address = [
                [
                    'id'            => Uuid::generate()->string,
                    'is_default'    => true,
                    'title'         => 'Alamat',
                    'address'       => 'JL. AMPERA RAYA, GG KANCIL RT001/09 JAKARTA SELATAN',
                    'customer_id'   => $customerId,
                    'created_at'    => Date::now(),
                    'updated_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'is_default'    => false,
                    'title'         => 'Alamat',
                    'address'       => 'JL. MANA AJA RT001/09 JAKARTA SELATAN',
                    'customer_id'   => $customerId,
                    'created_at'    => Date::now(),
                    'updated_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'is_default'    => false,
                    'title'         => 'Alamat',
                    'address'       => 'JL. YANG MANA RT001/09 JAKARTA SELATAN',
                    'customer_id'   => $customerId,
                    'created_at'    => Date::now(),
                    'updated_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'is_default'    => false,
                    'title'         => 'Alamat',
                    'address'       => 'JL. YANG ITU RT001/09 JAKARTA SELATAN',
                    'customer_id'   => $customerId,
                    'created_at'    => Date::now(),
                    'updated_at'    => Date::now(),
                ],
            ];
            DB::table('customer_address')->insert($address);
            DB::commit();
        } catch (\Exception $err) {
            DB::rollBack();
            dd($err->getMessage());
        }
    }
}
