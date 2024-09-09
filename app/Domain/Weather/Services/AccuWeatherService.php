<?php
namespace App\Domain\Weather\Services;

use App\Domain\Weather\Entities\AccuWeather;
use App\Domain\Weather\Entities\Location;

class AccuWeatherService
{
    public $location;
    public $service;
    public $currentWeatherData;
    function __construct(){
        $this->location = new Location();
        $this->service = new AccuWeather();
    }
    public function getCurrentWeather($payload){
        $city = $this->location->where('english_name',$payload['name'])->firstOrFail();
        $response = $this->service->currentWeather(['key'=>$city->key]);
        $this->currentWeatherData = $response->json();
        return $response;
    }

    public function generateTextMessage(){
        $text = "Info:\n";
        $text .= "📝".$this->currentWeatherData[0]['WeatherText']."\n";
        $text .= "🌡".(float)$this->currentWeatherData[0]['Temperature']['Metric']['Value'].' C';

        return $text;
    }
}
