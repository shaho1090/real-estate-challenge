<?php


namespace App;


use Carbon\Carbon;

class AppointmentConfig
{
    /*
     * duration in minutes
     */
    private const DURATION = 60;

    public static function duration(): int
    {
        return self::DURATION;
    }
}
