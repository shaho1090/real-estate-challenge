<?php


namespace App;


use App\ExternalServiceAdapters\DistanceEstimator;
use App\ExternalServiceAdapters\ZipCodeConverter;
use App\ExternalServices\DistanceEstimatorInterface;
use Exception;
use Illuminate\Support\Carbon;

class Distance
{
    private string $originZipCode;
    private string  $destinationZipCode;
    private Carbon $arrivalTime;
    private Carbon $departureTime;
    private DistanceEstimator $distanceEstimator;

    /**
     * @param string $fromZipCode
     * @param string $toZipCode
     */
    public function __construct(string $fromZipCode,string $toZipCode)
    {
        $this->originZipCode = $fromZipCode;
        $this->destinationZipCode = $toZipCode;
    }

    /**
     * @throws Exception
     */
    public function estimate(): Distance
    {
        $originLatLong = (new ZipCodeConverter($this->originZipCode))->getLatLong();
        $destinationLatLong =(new ZipCodeConverter($this->destinationZipCode))->getLatLong();

        $this->distanceEstimator = (new DistanceEstimator($originLatLong,$destinationLatLong));

        return $this;
    }

    /**
     * @throws Exception
     */
    public function inMinute(): int
    {
       return $this->distanceEstimator->getArrivalTime()
           ->diffInMinutes($this->distanceEstimator->getDepartureTime());
    }
}
