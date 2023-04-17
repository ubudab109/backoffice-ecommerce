<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\UserAdmin;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Webpatser\Uuid\Uuid;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = Uuid::generate()->string;
        Users::insert([
            'id' => $user_id,
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123123123'),
            'status' => 1,
            'type' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $role = Role::where('title', 'admin')->first();

        UserAdmin::insert([
            'id' => Uuid::generate()->string,
            'name' => 'admin',
            'user_id' => $user_id,
            'role_id' => $role->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
