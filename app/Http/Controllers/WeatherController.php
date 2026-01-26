<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\SavedLocation;

class WeatherController extends Controller
{
  public function getForecast(Request $request)
  {
    $request->validate([
      'lat' => 'required|numeric',
      'lng' => 'required|numeric',
      'name' => 'nullable|string|max:255',
    ]);

    $lat = $request->input('lat');
    $lng = $request->input('lng');
    $userAgent = 'MyWeatherWidget/1.0 (dtyronestrand@gmail.com)';

    $location= SavedLocation::where('lat', $lat)
      ->where('lng', $lng)
      ->first();

      $forecastUrl = null;

      if($location && $location->grid_request_url){
        $forecastUrl= $location->grid_request_url;
        } else {
        /** @var \Illuminate\Http\Client\Response $response */
        $response = Http::withHeaders([
          'User-Agent' => $userAgent,
        ])->get("https://api.weather.gov/points/{$lat},{$lng}");
        if ($response->successful()) {
          $data = $response->json();
          $forecastUrl = $data['properties']['forecast'] ?? null;

        SavedLocation::updateOrCreate(
          ['lat' => $lat, 'lng' => $lng],
          [
            'name' => $request->input('name', 'Unnamed Location'),
            'grid_request_url' => $forecastUrl,
          ]
        );
      } else {
        return response()->json(['error' => 'Failed to fetch grid data'], 500);
      }
  }
  $cacheKey="weather_forecast_{$lat}_{$lng}";
  $weatherData= Cache::remember($cacheKey, now()->addMinutes(60), function() use ($forecastUrl, $userAgent) {
    /** @var \Illuminate\Http\Client\Response $response */
    $response = Http::withHeaders([
      'User-Agent' => $userAgent,
    ])->get($forecastUrl);

    return $response->successful() ? $response->json()['properties']['periods'] : null;
  });
  if(!$weatherData){
    return response()->json(['error' => 'Failed to fetch forecast data'], 500);
  }
  return response()->json($weatherData);
  }
}
