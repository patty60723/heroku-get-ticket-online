<?php

use Illuminate\Database\Seeder;

class PrivSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('priv_group')->insert([
            'group_name' => 'superAdmin'
        ]);

        DB::table('priv_group')->insert([
            'group_name' => 'admin'
        ]);

        DB::table('priv_group')->insert([
            'group_name' => 'subAdmin'
        ]);
    }
}
