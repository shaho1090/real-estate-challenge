<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title'=> $this->title,
            'purpose'=> $this->purpose,
            'zip_code'=> $this->zip_code,
            'address'=> $this->address,
            'price'=> $this->price,
            'bedrooms'=> $this->bedrooms,
            'bathrooms'=> $this->bathrooms,
            'm_two'=> $this->m_two,
            'price_m_two' => $this->price_m_two,
            'type' => $this->type->title,
            'condition' => $this->condition->title,
            'landlord' => new UserResource($this->landlord()->first())
        ];
    }
}
