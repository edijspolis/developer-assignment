<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Seeder;

use DateTime;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $date = new DateTime();

        for ($i=0;$i<10;$i++) {
            Artisan::call("app:get-prices " . $date->format('Y-m-d'));

            $date->modify('-1 day');
        }
    }
}
