<?php

namespace App\Services;

use GuzzleHttp\Client;

use DateTime;
use stdClass;
use Exception;

class NORDPoolService
{
    const API_URL = 'https://dataportal-api.nordpoolgroup.com/api/';
    const AREA = 'LV';
    const CURRENCY = 'EUR';

    public function get_prices(DateTime $date): array
    {
        $url = self::API_URL . 'DayAheadPrices';
        $options = [
            'query' => [
                'date' => $date->format('Y-m-d'),
                'deliveryArea' => self::AREA,
                'currency' => self::CURRENCY
            ]
        ];

        $client = new Client();
        $response = $client->request('GET', $url, $options);
        $data = json_decode($response->getBody(), false, 512, JSON_THROW_ON_ERROR);

        if (is_null($data)) {
            throw new Exception('Invalid data');
        }

        if (!isset($data->multiAreaEntries)) {
            throw new Exception('No area entries');
        }

        $output = [];

        foreach ($data->multiAreaEntries as $row) {
            foreach ($row->entryPerArea as $code => $entry) {
                if ($code === self::AREA) {
                    $obj = new stdClass();

                    $obj->deliveryStart = new DateTime($row->deliveryStart);
                    $obj->deliveryEnd = new DateTime($row->deliveryEnd);
                    $obj->price = $entry;

                    $output[] = $obj;
                }
            }
        }

        return $output;
    }
}