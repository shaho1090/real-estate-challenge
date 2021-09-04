<?php


namespace App\ExternalServices;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class Here implements DistanceEstimatorInterface
{
    private string $apiKey;
    private string $apiAddress;
    private string $originLatLong;
    private string $destinationLatLong;
    private Carbon $departureTime;
    private Carbon $arrivalTime;

    public function __construct($originLatLong, $destinationLatLong)
    {
        $this->setConfiguration();
        $this->originLatLong = $originLatLong;
        $this->destinationLatLong = $destinationLatLong;
        $this->handle();
    }

    public function setConfiguration()
    {
        $this->apiKey = config('here.token');
        $this->apiAddress = config('here.address');
    }

    /**
     * @throws \Exception
     */
    private function handle()
    {
        if (is_null($this->apiKey)) {
            throw new \Exception('You should register and get and set api key from HERE website.');
        }

        $query = [
            'apiKey' => $this->apiKey,
            'transportMode' => 'car',
            'origin' => $this->originLatLong,
            'destination' => $this->destinationLatLong,
        ];

        $headers = [
            'Accept' => 'application/json'
        ];

        $response = Http::withHeaders($headers)->get(
            $this->apiAddress,
            $query
        );

        $response = $response->object()->routes;

        $this->departureTime = Carbon::parse($response[0]->sections[0]->departure->time);
        $this->arrivalTime = Carbon::parse($response[0]->sections[0]->arrival->time);
    }

    public function getDepartureTime(): Carbon
    {
        return $this->departureTime;
    }

    public function getArrivalTime(): Carbon
    {
        return $this->arrivalTime;
    }
}
