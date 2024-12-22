<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\NORDPoolService;
use App\Models\Price;

use DateTime;

class GetElectricityPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-prices {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call NORDPool API to get electricity prices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = new DateTime($this->argument('date'));
        $service = new NORDPoolService();

        if ($service) {
            $data = $service->get_prices($date);

            if ($data) {
                foreach ($data as $row) {
                    Price::upsert([
                        'delivery_start' => $row->deliveryStart,
                        'delivery_end' => $row->deliveryEnd,
                        'price' => $row->price
                    ], uniqueBy: ['delivery_start'], update: ['price']);
                }
            }
        }
    }
}
