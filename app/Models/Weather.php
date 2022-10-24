<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;

class Weather
{
    const URL = 'https://api.openweathermap.org/data/2.5';

    private $lat;
    private $lon;

    public function get($lat, $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;

        return [
            'today' => $this->today(),
            'future' => $this->future(),
        ];
    }

    public function today()
    {
        return $this->fire('weather');
    }

    public function future()
    {
        return $this->fire('forecast');
    }

    public function getTargetUrl($endpoint)
    {
        if(!str_starts_with($endpoint,'/')){
           $endpoint = '/' . $endpoint;
        }

        return self::URL . $endpoint;
    }

    public function fire($endpoint)
    {
        $query = $this->basePayload([
            'lat' => $this->lat,
            'lon' => $this->lon,
        ]);
        $response = Http::get($this->getTargetUrl($endpoint),$query);

        if($response->ok()){
            return $response->json();
        }

        throw new \Exception('remote server return ' . $response->status());
    }

    private function basePayload(array $payload)
    {
        return array_merge([
           'appid' => config('services.open_weather.app_id')
        ],$payload);
    }
}
