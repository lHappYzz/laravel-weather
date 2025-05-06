<?php

namespace App\Weather\DTO;
class WeatherDTO
{
    public function __construct(
        public string $city,
        public string $country,
        public string $temperature,
        public string $condition,
        public string $humidity,
        public string $windSpeed,
        public string $lastUpdated,
    ) {}
}
