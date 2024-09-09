<?php
return [
    'accuWeather'=>[
        'apiKey'=>env('ACCU_WEATHER_KEY'),
        'endpoint'=>'http://dataservice.accuweather.com'
    ],
    'openWeather'=>[
        'apiKey'=>env('OPEN_WEATHER_KEY'),
        'endpoint'=>'https://api.openweathermap.org'
    ]
];
