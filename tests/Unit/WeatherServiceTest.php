<?php

use App\Exceptions\WeatherServiceException;
use App\Services\WeatherService;
use App\Weather\Contracts\WeatherProviderInterface;
use App\Weather\DTO\WeatherDTO;
use App\Weather\Exceptions\WeatherProviderException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class WeatherServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.weather_api.key', 'test-key');
        Config::set('services.weather_api.base_uri', 'https://example.com');
        Config::set('services.weather_api.log_channel', 'test_weather');
        Config::set('services.weather_api.api_provider_log_channel', 'test_weather');
        Config::set('services.weather_api.cache_key', 'test_cache_weather');
    }

    public function test_fetch_returns_resource_when_cache_is_empty(): void
    {
        $city = 'Kyiv';
        $dto = new WeatherDTO(
            city: 'Kyiv',
            country: 'Ukraine',
            temperature: 20,
            condition: 'Sunny',
            humidity: 50,
            windSpeed: 5,
            lastUpdated: now(),
        );

        $provider = Mockery::mock(WeatherProviderInterface::class);
        $provider
            ->shouldReceive('getWeather')
            ->once()
            ->with($city)
            ->andReturn($dto);

        Cache::shouldReceive('remember')
            ->once()
            ->withArgs(function ($key, $time, $callback) use ($dto) {
                return $callback() === $dto;
            })
            ->andReturn($dto);

        $service = new WeatherService($provider);
        $result = $service->fetch($city);

        $this->assertInstanceOf(
            \App\Http\Resources\WeatherResource::class,
            $result
        );
    }

    public function test_fetch_throws_service_exception_when_provider_fails(): void
    {
        $this->expectException(WeatherServiceException::class);

        $city = 'WrongCityName';
        $providerMock = Mockery::mock(WeatherProviderInterface::class);

        $providerMock
            ->shouldReceive('getWeather')
            ->with($city)
            ->andThrow(new WeatherProviderException('Error'));

        $service = new WeatherService($providerMock);
        $service->fetch($city);
    }
}
