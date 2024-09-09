<?php
namespace App\Domain\Currency\Services;

use App\Domain\Currency\Clients\CbrClient;
use App\Domain\Currency\Entities\Currency;
use App\Domain\Weather\Entities\Location;
use App\Domain\Weather\Entities\OpenWeatherMap;
use App\Utils\CurrencyXmlParser;

class CurrencyService
{
    public $currency;
    public $client;
    public $currrencies;

    function __construct(){
        $this->client = new CbrClient();
    }
    public function getCurrencies($payload){
        $currencies = Currency::query()->paginate(15);
        return $currencies;
    }

    public function loadCurrency(){
        $data = $this->client->getCurrencies();
        if($data->successful()){
            $this->currrencies = new CurrencyXmlParser($data->body());
            return $this->currrencies->toArray();
        }
        return false;
        
    }   

    public function saveCurrencies(){
        if(!empty($this->currrencies->toArray())&&$data=$this->currrencies->toArray()){
            foreach($data['CcyNtry'] as $item){
                $model = Currency::updateOrCreate([
                   'cbr_id'=>$item['@attributes']['ID'] ],[
                   'name'=>$item['CcyNm_RU'],
                   'nominal'=>$item['Nominal'],
                   'value'=>$item['Rate'],
                   'rate'=>$item['Rate'],
                ]);
            }

            return true;
        }
        return false;
    }

}

