<?php

namespace App\Http\Controllers;

use App\Models\Weather;
use App\Practice\Validation\Validator;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    public function __invoke(Validator $validator, Weather $weather)
    {
        $validated = $validator->validate([
            'lat' => 'required',
            'lon' => 'required',
        ]);

        $key = "weather:" . $validated['lat'] . ':' . $validated['lon'];
        return Cache::remember($key, 60 * 60 ,fn() =>
            $weather->get($validated['lat'], $validated['lon'])
        );
    }
}
