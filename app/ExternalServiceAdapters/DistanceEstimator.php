<?php


namespace App\ExternalServiceAdapters;


use App\ExternalServices\DistanceEstimatorInterface;
use App\ExternalServices\Here;
use Illuminate\Support\Carbon;

class DistanceEstimator
{

    private string $originLatLong;
    private string $destinationLatLong;
    private string $estimatorService;
    private DistanceEstimatorInterface $response;

    public function __construct($originLatLong, $destinationLatLong)
    {
        $this->setExternalEstimatorService();
        $this->originLatLong = $originLatLong;
        $this->destinationLatLong = $destinationLatLong;
        $this->handle();
    }

    public function handle()
    {
       $this->response = (new $this->estimatorService($this->originLatLong, $this->destinationLatLong));
    }

    private function setExternalEstimatorService()
    {
        $this->estimatorService = Here::class;
    }

    /**
     * @return Carbon
     */
    public function getDepartureTime(): Carbon
    {
        return $this->response->getDepartureTime();
    }

    /**
     * @return Carbon
     */
    public function getArrivalTime(): Carbon
    {
       return $this->response->getArrivalTime();
    }
}
