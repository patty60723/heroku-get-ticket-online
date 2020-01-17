<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstrumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [1 => '長笛', 2 => '短笛', 3 => '豎笛', 4 => '低音豎笛', 5 => '雙簧管', 6 => '薩克斯風', 7 => '法國號', 8 => '小號', 9 => '長號', 10 => '上低音號', 11 => '低音號', 12 => '打擊', 13 => '鋼琴', 14 => '其他'];
        foreach ($data as $key => $value) {
            DB::table('instruments')->insert([
                'instru_name' => $value,
                'instru_no'   => $key,
                'event_code'  => '20_themovements',
                'created_at'  => time(),
                'updated_at'  => time()
            ]);
        }
    }
}
