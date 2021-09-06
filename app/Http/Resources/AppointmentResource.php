<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'employee' => new UserResource($this->employee),
            'home' => new HomeResource($this->home),
            'expected_date_time' => $this->date,
            'visited_start_time' => $this->start_time,
            'distance_estimated_time' => $this->distance_estimated_time,
            'probable_employee_free_time' => $this->probableEmployeeFreeTime(),
            'visited_end_time' => $this->end_time,
        ];
    }
}
