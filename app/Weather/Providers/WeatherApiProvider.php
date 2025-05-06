<?php

namespace App\Weather\Providers;

use App\Weather\Contracts\WeatherProviderInterface;
use App\Weather\DTO\WeatherDTO;
use App\Weather\Exceptions\WeatherProviderException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherApiProvider implements WeatherProviderInterface
{
    /**
     * Key needed to use API.
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * Base URL that used in every call to API endpoints.
     *
     * @var string
     */
    protected string $baseUri;

    /**
     * Log channel that encapsulates a provider level logging.
     *
     * @var string
     */
    protected string $logChannel;

    public function __construct()
    {
        $this->apiKey = config('services.weather_api.key');
        $this->baseUri = config('services.weather_api.base_uri');
        $this->logChannel = config('services.weather_api.api_provider_log_channel');
    }

    /**
     * @inheritDoc
     */
    public function getWeather(string $city): WeatherDTO
    {
        $url = $this->baseUri . 'current.json';
        try {
            $response = Http::timeout(30)->get($url, [
                'q' => $city,
                'key' => $this->apiKey,
            ]);
        } catch (ConnectionException $e) {
            Log::channel($this->logChannel)->error($e->getMessage());
            throw new WeatherProviderException($e);
        }

        if (!$response->successful()) {
            Log::channel($this->logChannel)->error($response->body());
            throw new WeatherProviderException($response->body());
        }

        $data = $response->json();

        if(isset($data['error'])) {
            Log::channel($this->logChannel)->error($response->body());
            throw new WeatherProviderException($response->body());
        }

        $result = [
            'city' => $data['location']['name'],
            'country' => $data['location']['country'],
            'temperature' => $data['current']['temp_c'],
            'condition' => $data['current']['condition']['text'],
            'humidity' => $data['current']['humidity'],
            'windSpeed' => $data['current']['wind_kph'],
            'lastUpdated' => $data['current']['last_updated'],
        ];

        Log::channel($this->logChannel)->info($result);

        return new WeatherDTO(...$result);
    }
}
