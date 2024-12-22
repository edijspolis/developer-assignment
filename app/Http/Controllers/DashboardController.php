<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Price;

use DateTime;
use DateTimeZone;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'date' => 'date'
        ]);

        $utcTimezone = new DateTimeZone('UTC');
        $rigaTimezone = new DateTimeZone('Europe/Riga');

        $dateStart = new DateTime($request->date, $rigaTimezone);
        $dateEnd = clone $dateStart;
        $dateFrontend = $dateStart->format('Y-m-d');

        $dateStart->setTime(0, 0);
        $dateEnd->setTime(23, 59, 59);

        $dateStart->setTimezone($utcTimezone);
        $dateEnd->setTimezone($utcTimezone);

        $prices = Price::whereBetween('delivery_start', [
                $dateStart->format('Y-m-d H:i:s'),
                $dateEnd->format('Y-m-d H:i:s')
            ])
            ->get();
        
        $enabledDates =  DB::table('prices')
            ->select([DB::raw('DATE(delivery_end) as date')])
            ->groupBy('date')
            ->get();

        return Inertia::render('Dashboard', [
            'prices' => $prices,
            'date' => $dateFrontend,
            'enabled-dates' => $enabledDates->pluck('date')->toArray()
        ]);
    }
}
