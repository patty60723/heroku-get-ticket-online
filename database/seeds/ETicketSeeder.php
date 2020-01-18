<?php

use Illuminate\Database\Seeder;

class ETicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        setlocale(LC_ALL, 'en_US.UTF-8');
        if (($handle = fopen(database_path("data/eTicketData.csv"), "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                DB::table('eticket')->insert([
                    'event_code'  => '20_themovements',
                    'email'       => $data[0],
                    'name'        => $data[1],
                    'phone'       => $data[2],
                    'number'      => (int)$data[3],
                    'code'        => $data[4],
                    'status'      => 0,
                    'created_at'  => date('Y-m-d H:i:s', time()),
                    'updated_at'  => date('Y-m-d H:i:s', time())
                ]);
            }
            fclose($handle);
        }
    }
}
