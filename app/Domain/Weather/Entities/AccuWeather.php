<?php
namespace App\Domain\Weather\Entities;

use Illuminate\Support\Facades\Http;

class AccuWeather
{
    protected $endpoint;
    protected $apiKey;
    static protected $currentWeatherUrl = '/currentconditions/v1/';
    function __construct(){
        $this->endpoint = config('weather.accuWeather.endpoint');
        $this->apiKey = config('weather.accuWeather.apiKey');
    }

    public function currentWeather($payload){
        $response = Http::get($this->endpoint.self::$currentWeatherUrl.$payload['key'], [
            'apikey' => $this->apiKey
        ]);
        return $response;
    }   
}
