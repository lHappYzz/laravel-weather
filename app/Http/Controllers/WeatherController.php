<?php

namespace App\Http\Controllers;

use App\Exceptions\WeatherServiceException;
use App\Http\Requests\GetWeatherRequest;
use App\Services\WeatherService;
use Illuminate\Contracts\View\View;

class WeatherController extends Controller
{
    /**
     * @param WeatherService $weatherService
     */
    public function __construct(protected readonly WeatherService $weatherService) {}

    /**
     * @param GetWeatherRequest $request
     * @return View
     */
    public function show(GetWeatherRequest $request): View
    {
        try {
            $weather = $this->weatherService->fetch($request->city);
        } catch (WeatherServiceException $e) {
            return view('city_weather', ['errorMessage' => $e->getMessage()]);
        }

        return view('city_weather', ['weather' => $weather->toArray($request)]);
    }
}
