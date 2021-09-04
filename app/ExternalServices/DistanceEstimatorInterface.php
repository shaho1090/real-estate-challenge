<?php


namespace App\ExternalServices;


use Illuminate\Support\Carbon;

interface DistanceEstimatorInterface
{

    public function __construct($originLatLong, $destinationLatLong);

    public function setConfiguration();

    /**
     * @return Carbon
     */
    public function getDepartureTime(): Carbon;

    /**
     * @return Carbon
     */
    public function getArrivalTime(): Carbon;
}
