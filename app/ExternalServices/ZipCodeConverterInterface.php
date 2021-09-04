<?php


namespace App\ExternalServices;


interface ZipCodeConverterInterface
{
    public function __construct(string $zipCode);

    public function setConfiguration();

    public function getLatLong();
}
