<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class NewPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set("Asia/Jakarta");
        $uuidParent = [
            'dashboard'                 => Uuid::generate()->string,
            'role_management'           => Uuid::generate()->string,
            'user_management'           => Uuid::generate()->string,
            'category_management'       => Uuid::generate()->string,
            'banner_management'         => Uuid::generate()->string,
            'product_management'        => Uuid::generate()->string,
            'inventory_management'      => Uuid::generate()->string,
            'voucher_management'        => Uuid::generate()->string,
            'customer_management'       => Uuid::generate()->string,
            'transaction_management'    => Uuid::generate()->string,
        ];

        DB::beginTransaction();
        try {

            $permissionParent =
                [
                    [
                        'id' => $uuidParent['dashboard'],
                        'title' => 'dashboard',
                        'created_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => $uuidParent['role_management'],
                        'title' => 'role_management',
                        'created_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => $uuidParent['user_management'],
                        'title' => 'user_management',
                        'created_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => $uuidParent['category_management'],
                        'title' => 'category_management',
                        'created_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => $uuidParent['banner_management'],
                        'title' => 'banner_management',
                        'created_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => $uuidParent['product_management'],
                        'title' => 'product_management',
                        'created_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => $uuidParent['inventory_management'],
                        'title' => 'inventory_management',
                        'created_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => $uuidParent['voucher_management'],
                        'title' => 'voucher_management',
                        'created_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => $uuidParent['customer_management'],
                        'title' => 'customer_management',
                        'created_at' => date('Y-m-d H:i:s'),
                    ],
                    [
                        'id' => $uuidParent['transaction_management'],
                        'title' => 'transaction_management',
                        'created_at' => date('Y-m-d H:i:s'),
                    ],
                ];
            
            $permissionChild = [
                /* PERMISSION ROLE MANAGEMENT */
                [
                    'id'            => Uuid::generate()->string,
                    'title'         => 'role_management_list',
                    'parent_id'     => $uuidParent['role_management'],
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['role_management'],
                    'title'         => 'role_management_add',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['role_management'],
                    'title'         => 'role_management_detail',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['role_management'],
                    'title'         => 'role_management_edit',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['role_management'],
                    'title'         => 'role_management_delete',
                    'created_at'    => Date::now(),
                ],
                /* END ROLE MANAGEMENT */

                /* PERMISSION USER MANAGEMENT */
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['user_management'],
                    'title'         => 'user_management_list',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['user_management'],
                    'title'         => 'user_management_add',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['user_management'],
                    'title'         => 'user_management_detail',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['user_management'],
                    'title'         => 'user_management_edit',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['user_management'],
                    'title'         => 'user_management_delete',
                    'created_at'    => Date::now(),
                ],
                /* END ROLE USER MANAGEMENT */
                 
                /* PERMISSION CATEGORY MANAGAMENT */
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['category_management'],
                    'title'         => 'category_management_list',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['category_management'],
                    'title'         => 'category_management_add',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['category_management'],
                    'title'         => 'category_management_detail',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['category_management'],
                    'title'         => 'category_management_edit',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['category_management'],
                    'title'         => 'category_management_delete',
                    'created_at'    => Date::now(),
                ],
                /* END PERMISSION CATEGORY MANAGEMENT */

                /* PERMISSION BANNER MANAGAMENT */
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['banner_management'],
                    'title'         => 'banner_management_list',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['banner_management'],
                    'title'         => 'banner_management_add',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['banner_management'],
                    'title'         => 'banner_management_detail',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['banner_management'],
                    'title'         => 'banner_management_edit',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['banner_management'],
                    'title'         => 'banner_management_delete',
                    'created_at'    => Date::now(),
                ],
                /* END PERMISSION BANNER MANAGEMENT */

                /* PERMISSION PRODUCT MANAGAMENT */
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['product_management'],
                    'title'         => 'product_management_list',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['product_management'],
                    'title'         => 'product_management_add',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['product_management'],
                    'title'         => 'product_management_detail',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['product_management'],
                    'title'         => 'product_management_edit',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['product_management'],
                    'title'         => 'product_management_delete',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['product_management'],
                    'title'         => 'product_management_inventory',
                    'created_at'    => Date::now(),
                ],
                /* END PERMISSION PRODUCT MANAGEMENT */

                /* PERMISSION INVENTORY MANAGAMENT */
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['inventory_management'],
                    'title'         => 'inventory_management_list',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['inventory_management'],
                    'title'         => 'inventory_management_add',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['inventory_management'],
                    'title'         => 'inventory_management_detail',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['inventory_management'],
                    'title'         => 'inventory_management_edit',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['inventory_management'],
                    'title'         => 'inventory_management_delete',
                    'created_at'    => Date::now(),
                ],
                /* END PERMISSION INVENTORY MANAGEMENT */

                /* PERMISSION VOUCHER MANAGAMENT */
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['voucher_management'],
                    'title'         => 'voucher_management_list',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['voucher_management'],
                    'title'         => 'voucher_management_add',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['voucher_management'],
                    'title'         => 'voucher_management_detail',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['voucher_management'],
                    'title'         => 'voucher_management_edit',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['voucher_management'],
                    'title'         => 'voucher_management_delete',
                    'created_at'    => Date::now(),
                ],
                /* END PERMISSION VOUCHER MANAGEMENT */

                /* PERMISSION CUSTOMER MANAGAMENT */
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['customer_management'],
                    'title'         => 'customer_management_list',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['customer_management'],
                    'title'         => 'customer_management_detail',
                    'created_at'    => Date::now(),
                ],
                /* END PERMISSION CUSTOMER MANAGEMENT */

                /* PERMISSION TRANSACTION MANAGAMENT */
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['transaction_management'],
                    'title'         => 'transaction_management_list',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['transaction_management'],
                    'title'         => 'transaction_management_detail',
                    'created_at'    => Date::now(),
                ],
                [
                    'id'            => Uuid::generate()->string,
                    'parent_id'     => $uuidParent['transaction_management'],
                    'title'         => 'transaction_management_edit',
                    'created_at'    => Date::now(),
                ],
                /* END PERMISSION TRANSACTION MANAGEMENT */
            ];
            foreach ($permissionParent as $parent) {
                Permission::updateOrCreate([
                    'title' => $parent['title']
                ], [
                    'id' => $parent['id'],
                    'title' => $parent['title'],
                    'created_at' => $parent['created_at']
                ]);
            }
            foreach ($permissionChild as $child) {
                Permission::updateOrCreate([
                    'title' => $child['title']
                ], [
                    'id' => $child['id'],
                    'parent_id' => $child['parent_id'],
                    'title' => $child['title'],
                    'created_at' => $child['created_at']
                ]);
            }
            DB::commit();
        } catch (\Exception $err) {
            DB::rollBack();
            dd($err->getMessage());
        }
    }
}
