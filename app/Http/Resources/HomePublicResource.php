<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HomePublicResource extends JsonResource
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
            'title'=> $this->title,
            'purpose'=> $this->purpose,
            'address'=> $this->address,
            'price'=> $this->price,
            'bedrooms'=> $this->bedrooms,
            'bathrooms'=> $this->bathrooms,
            'm_two'=> $this->m_two,
            'price_m_two' => $this->price_m_two,
            'type' => $this->type->title,
            'condition' => $this->condition->title,
        ];
    }
}
