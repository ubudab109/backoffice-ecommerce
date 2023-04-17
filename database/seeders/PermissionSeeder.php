<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Webpatser\Uuid\Uuid;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set("Asia/Jakarta");
        $permission = [
            [
                'id' => Uuid::generate()->string,
                'title' => 'dashboard',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => Uuid::generate()->string,
                'title' => 'role_management',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => Uuid::generate()->string,
                'title' => 'user_management',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ];

        Permission::insert($permission);
    }
}
