<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([PrivSeeder::class,
                     AdminUserSeeder::class,
                     EventsSeeder::class,
                     InstrumentsSeeder::class,
                     FTicketSeeder::class
                    ]);
    }
}
