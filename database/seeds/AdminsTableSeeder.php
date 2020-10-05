<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin')->delete();
        DB::table('role_user')->delete();
        DB::table('roles')->delete();
        DB::table('permission_role')->delete();

        DB::table('admin')->insert([
        	['id' => 1, 'username' => 'admin', 'email' => 'admin@trioangle.com', 'password' => bcrypt('spiffy'), 'status' => 'Active', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'username' => 'subadmin', 'email' => 'subadmin@trioangle.com', 'password' => bcrypt('subadmin123'), 'status' => 'Active', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'username' => 'accountant', 'email' => 'accountant@trioangle.com', 'password' => bcrypt('accountant123'), 'status' => 'Active', 'created_at' => date('Y-m-d H:i:s')]
        ]);

        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin', 'display_name' => 'Admin', 'description' => 'Admin User', 'created_at' => '2016-04-17 00:00:00','updated_at' => '2016-04-17 00:00:00'],
            ['id' => 2, 'name' => 'subadmin', 'display_name' => 'subadmin', 'description' => 'subadmin', 'created_at' => '2016-04-17 00:10:00','updated_at' => '2016-04-17 00:00:00'],
            ['id' => 3, 'name' => 'accountant', 'display_name' => 'accountant', 'description' => 'accountant', 'created_at' => '2016-04-17 00:10:00','updated_at' => '2016-04-17 00:00:00']
        ]);

        DB::table('role_user')->insert([
            ['user_id' => 1, 'role_id' => '1'],
            ['user_id' => 2, 'role_id' => '2'],
            ['user_id' => 3, 'role_id' => '3']
        ]);

        $permissions = DB::table('permissions')->get();
        $subadmin_permissions = array(1, 2, 3, 9, 14);
        $accountant_permissions = array(8, 9, 10, 14, 17);

        $permissions_data = [];

        // Admin Permissions
        foreach ($permissions as $key => $value) {
            $permissions_data[] = array('permission_id' => $value->id, 'role_id' => '1');
        }

        // Subadmin Permissions
        foreach ($permissions->whereIn('id',$subadmin_permissions) as $key => $value) {
            $permissions_data[] = array('permission_id' => $value->id, 'role_id' => '2');
        }

        // Subadmin Permissions
        foreach ($permissions->whereIn('id',$accountant_permissions) as $key => $value) {
            $permissions_data[] = array('permission_id' => $value->id, 'role_id' => '3');
        }

        DB::table('permission_role')->insert($permissions_data);
    }
}