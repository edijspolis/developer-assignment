<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Price;

use DateTime;
use DateTimeZone;

class PriceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/prices/current-hour",
     *     summary="Get electricity price for current hour",
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Price not found for current hour")
     * )
     */
    public function currentHour(Request $request)
    {
        $utcTimezone = new DateTimeZone('UTC');
        $rigaTimezone = new DateTimeZone('Europe/Riga');

        $datetime = new DateTime('now', $rigaTimezone);
        $datetime->setTimezone($utcTimezone);

        $price = Price::where('delivery_start', '<=', $datetime->format('Y-m-d H:i:s'))
            ->where('delivery_end', '>', $datetime->format('Y-m-d H:i:s'))
            ->first();

        if (is_null($price)) {
            return response()->json([
                'message' => 'Price not found for current hour',
            ], 404);
        }

        return response()->json([
            'delivery_start' => (new DateTime($price->delivery_start, $utcTimezone))->setTimezone($rigaTimezone)->format('Y-m-d H:i:s'),
            'delivery_end' => (new DateTime($price->delivery_end, $utcTimezone))->setTimezone($rigaTimezone)->format('Y-m-d H:i:s'),
            'price' => $price->price_multiplied,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/prices/next-hour",
     *     summary="Get electricity price for next hour",
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Price not found for next hour")
     * )
     */
    public function nextHour(Request $request)
    {
        $utcTimezone = new DateTimeZone('UTC');
        $rigaTimezone = new DateTimeZone('Europe/Riga');

        $datetime = new DateTime('now', $rigaTimezone);
        $datetime->setTimezone($utcTimezone);
        $datetime->modify('+1 hour');

        $price = Price::where('delivery_start', '<=', $datetime->format('Y-m-d H:i:s'))
            ->where('delivery_end', '>', $datetime->format('Y-m-d H:i:s'))
            ->first();

        if (is_null($price)) {
            return response()->json([
                'message' => 'Price not found for next hour',
            ], 404);
        }

        return response()->json([
            'delivery_start' => (new DateTime($price->delivery_start, $utcTimezone))->setTimezone($rigaTimezone)->format('Y-m-d H:i:s'),
            'delivery_end' => (new DateTime($price->delivery_end, $utcTimezone))->setTimezone($rigaTimezone)->format('Y-m-d H:i:s'),
            'price' => $price->price_multiplied,
        ]);
    }
}
