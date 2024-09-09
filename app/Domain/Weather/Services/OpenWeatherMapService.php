<?php
namespace App\Domain\Weather\Services;

use App\Domain\Weather\Entities\Location;
use App\Domain\Weather\Entities\OpenWeatherMap;

class OpenWeatherMapService
{
    public $location;
    public $service;
    public $currentWeatherData;
    function __construct(){
        $this->location = new Location();
        $this->service = new OpenWeatherMap();
    }
    public function getCurrentWeather($payload){
        $city = $this->location->where('english_name',$payload['name'])->firstOrFail();
        $response = $this->service->currentWeather(['lat'=>$city->lat,'lon'=>$city->long]);
        $this->currentWeatherData = $response->json();
        return $response;
    }

    public function generateTextMessage(){
        $text = "Info:\n";
        $text .= "📝".$this->currentWeatherData['weather'][0]['main']."\n\n";
        $text .= "🌡".(float)$this->currentWeatherData['main']['temp']-273;
        $text .= " C";
        return $text;
    }
}
