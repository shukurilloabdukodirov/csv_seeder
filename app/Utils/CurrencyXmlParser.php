<?php
namespace App\Utils;

use Orchestra\Parser\Xml\Facade as XmlParser;

class CurrencyXmlParser
{
    public $xmlString;
    public $data;
    public function __construct($xmlString){
        $this->xmlString = $xmlString;
    }
    public function toArray(){
        
        $xmlResponse = simplexml_load_string($this->xmlString );

        $this->data = json_decode(json_encode($xmlResponse), true);
        // $this->data = self::$xmlParser->parse([
        //     'id'=>['uses'=>'Valute::ID'],
        //     'name'=>['uses'=>'Valute.Name'],
        //     'rate'=>['uses'=>'Valute.VunitRate'],
        //     'value'=>['uses'=>'Valute.Value'],
        //     'nominal'=>['uses'=>'Valute.Nominal']
        // ]);
        return $this->data;
    }
    
}
