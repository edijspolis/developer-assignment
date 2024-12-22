<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'delivery_start',
        'delivery_end',
        'price'
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'delivery_start' => 'datetime',
        'delivery_end' => 'datetime',
        'price' => 'double'
    ];

    protected static function booted(): void
    {
        static::retrieved(function (Price $row) {
            $row->price_multiplied = round($row->price * env('ELECTRICITY_PRICE_MULTIPLIER'), 2);
        });
    }
}
