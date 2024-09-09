<?php
namespace App\Domain\Currency\Clients;

use Illuminate\Support\Facades\Http;

class CbrClient
{
    protected $endpoint;
    function __construct(){
        $this->endpoint = config('currency.cbr.endpoint');
    }

    public function getCurrencies(){
        $response = Http::get($this->endpoint);
        return $response;
    }   
}
