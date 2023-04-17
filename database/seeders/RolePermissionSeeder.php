<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use Webpatser\Uuid\Uuid;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = Permission::get()->toArray();
        $role = Role::where('title', 'admin')->first();
        $rolePermission = [];
        date_default_timezone_set("Asia/Jakarta");
        for ($i=0; $i < count($permission); $i++) { 
            $rolePermission[$i]['id'] = Uuid::generate()->string;
            $rolePermission[$i]['created_at'] = date('Y-m-d H:i:s');
            $rolePermission[$i]['permission_id'] = $permission[$i]['id'];
            $rolePermission[$i]['role_id'] = $role->id;
        }
        
        RolePermission::insert($rolePermission);
    }
}
