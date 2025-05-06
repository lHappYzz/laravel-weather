<?php

namespace App\Weather\Contracts;

use App\Weather\DTO\WeatherDTO;
use App\Weather\Exceptions\WeatherProviderException;

/**
 * Interface for weather data providers.
 *
 * Defines the contract for classes that provide weather data from various sources.
 */
interface WeatherProviderInterface
{
    /**
     * Retrieves current weather for the given city.
     *
     * @param string $city
     * @throws WeatherProviderException If the API request fails.
     * @return WeatherDTO
     */
    public function getWeather(string $city): WeatherDTO;
}
