<?php
namespace App\Domain\Weather\Entities;

use Illuminate\Support\Facades\Http;

class OpenWeatherMap
{
    protected $endpoint;
    protected $apiKey;
    static protected $currentWeatherUrl = '/data/2.5/weather';
    function __construct(){
        $this->endpoint = config('weather.openWeather.endpoint');
        $this->apiKey = config('weather.openWeather.apiKey');
    }

    public function currentWeather($payload){
        $response = Http::get($this->endpoint.self::$currentWeatherUrl, [
            'lat' =>$payload['lat'],
            'lon' =>$payload['lon'],
            'appid' => $this->apiKey
        ]);
        return $response;
    }   
}
