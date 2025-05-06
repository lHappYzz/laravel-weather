<?php

namespace App\Services;

use App\Exceptions\WeatherServiceException;
use App\Http\Resources\WeatherResource;
use App\Weather\Contracts\WeatherProviderInterface;
use App\Weather\Exceptions\WeatherProviderException;
use Illuminate\Support\Facades\Cache;

/**
 * Service to fetch and process weather data.
 *
 * This service interacts with a weather provider to retrieve weather information
 * for a given city. Layer for a business logic.
 */
class WeatherService
{
    /**
     * @var string
     */
    protected string $cacheKey;

    /**
     * @param WeatherProviderInterface $weatherProvider
     */
    public function __construct(
        protected WeatherProviderInterface $weatherProvider,
    ) {
        $this->cacheKey = config('services.weather_api.cache_key');
    }

    /**
     * Fetch current weather data for a given city.
     *
     * @param string $city
     * @return WeatherResource
     * @throws WeatherServiceException Can be used to describe to the client what went wrong.
     */
    public function fetch(string $city): WeatherResource
    {
        try {
            $weather = Cache::remember(
                $this->cacheKey . $city,
                now()->addMinutes(60),
                fn() => $this->weatherProvider->getWeather($city)
            );
        } catch (WeatherProviderException $e) {
            throw new WeatherServiceException($e->getMessage());
        }

        return new WeatherResource($weather);
    }
}
