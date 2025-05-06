<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'city' => $this->city,
            'country' => $this->country,
            'temperature' => $this->temperature . '°C',
            'condition' => ucfirst($this->condition),
            'humidity' => $this->humidity . '%',
            'windSpeed' => $this->windSpeed . 'км/г',
            'lastUpdated' => $this->lastUpdated,
        ];
    }
}
