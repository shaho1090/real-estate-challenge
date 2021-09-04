<?php


namespace App\Models;


use Exception;
use Illuminate\Validation\UnauthorizedException;

class MyAppointment
{
    /**
     * @throws Exception
     */
    public function getLatest()
    {
        if(!auth()->check()){
            throw new UnauthorizedException();
        }

        if($latestAppointment = auth()->user()->employeeAppointments()->where('end_time','<>',null)->latest()->first()){
            return $latestAppointment;
        }

        throw new Exception('There is no latest appointment!');
    }
}
