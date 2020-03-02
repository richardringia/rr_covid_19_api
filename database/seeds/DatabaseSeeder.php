<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
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
        DB::unprepared(file_get_contents((__DIR__ . '/sql/virus_data_status.sql')));
        DB::unprepared(file_get_contents((__DIR__ . '/sql/virus_data_types.sql')));
        Artisan::call('passport:install');
    }
}
