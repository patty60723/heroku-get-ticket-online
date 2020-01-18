<?php

use Illuminate\Database\Seeder;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->insert([
            'event_code'     => '20_themovements',
            'event_name'     => '《那一年，我們一起演奏的樂章》｜2020 年板城管樂團啟程音樂會',
            'event_datetime' => date('Y-m-d H:i:s', strtotime('2020/01/19 14:30'))
        ]);
    }
}
