<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' =>$this->id,
            'name' => $this->name,
            'family' => $this->family,
            'email' => $this->email,
            'phone' => $this->phone,
            'current_appointment' => new AppointmentResource($this->getCurrentAppointment()),
        ];
    }
}
