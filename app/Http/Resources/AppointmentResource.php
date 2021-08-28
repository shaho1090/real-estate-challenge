<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'employee' => new UserResource($this->employee),
            'home' => new HomeResource($this->home),
            'expected_date_time' => $this->date,
            'visited_start_time' => $this->start_time,
            'visited_end_time' => $this->end_time
        ];
    }
}
