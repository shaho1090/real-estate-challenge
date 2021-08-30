<?php


namespace App;



class RealStateOffice
{
    private string $zipcode;

    public function __construct()
    {
        $this->zipcode = 'cm27pj';
    }

    public function getZipcode(): string
    {
        return $this->zipcode;
    }
}
