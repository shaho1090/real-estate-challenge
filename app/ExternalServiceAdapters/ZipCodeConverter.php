<?php


namespace App\ExternalServiceAdapters;


use App\ExternalServices\Postcode;
use App\ExternalServices\ZipCodeConverterInterface;

class ZipCodeConverter
{
    private string $converterService;
    private string $zipCode;
    private ZipCodeConverterInterface $response;

    public function __construct($zipCode)
    {
        $this->setExternalConverterService();
        $this->zipCode = $zipCode;
        $this->handle();
    }

    private function setExternalConverterService()
    {
        $this->converterService = Postcode::class;
    }

    public function handle()
    {
        $this->response = (new $this->converterService($this->zipCode));
    }

    public function getLatLong():string
    {
        return str_replace(' ', '', $this->response->getLatLong());
    }
}
