<?php

namespace Database\Seeders;

use App\Domain\Weather\Entities\Location;
use App\Utils\JsonParser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new JsonParser('Domain/Weather/Data/locations.json');
        $array = $data->toArray();
        $cities = $array;
        foreach ($cities as $city){
            $model = new Location();
            $model->key = $city['Key'];
            $model->english_name = $city['EnglishName'];
            $model->lat = $city['lat'];
            $model->long = $city['lon'];
            $model->save();
        }
    }
}
