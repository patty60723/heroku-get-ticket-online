<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 新增最高權限管理者
        DB::table('users')->insert([
            'name'      => 'superAdmin',
            'email'     => 'bcwindband@gmail.com',
            'privGroup' => 1,
            'password'  => bcrypt('103t/6ej03m,4wj06')
        ]);

        DB::table('users')->insert([
            'name'      => 'bcadmin',
            'email'     => 'bcadmin@gmail.com',
            'privGroup' => 2,
            'password'  => bcrypt('103t/6ej03m,4wj06')
        ]);

        DB::table('users')->insert([
            'name'      => 'bcGuestAdmin',
            'email'     => 'bcguestadmin@gmail.com',
            'privGroup' => 3,
            'password'  => bcrypt('103t/6ej03m,4wj06')
        ]);
    }
}