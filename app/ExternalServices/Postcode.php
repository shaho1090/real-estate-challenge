<?php


namespace App\ExternalServices;


use Illuminate\Support\Facades\Http;

class Postcode implements ZipCodeConverterInterface
{
    private string $apiAddress;
    private string $zipCode;

    public function __construct($zipCode)
    {
        $this->zipCode = $zipCode;
        $this->apiAddress = config('postcodes.address');
    }

    public function getApiAddress()
    {
        return $this->apiAddress;
    }

    /**
     * @throws \Exception
     */
    public function getLatLong(): string
    {
        $headers = [
            'Accept' => 'application/json'
        ];

        $response = Http::withHeaders($headers)->get(
            $this->apiAddress . $this->zipCode
        );

        if ($response->status() !== 200) {
            throw new \Exception('Converting zip code was not successful.');
        }

        return ($response->object()->result->latitude . ',' . $response->object()->result->longitude);
    }
}
