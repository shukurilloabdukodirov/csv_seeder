<?php
namespace App\Utils;

class JsonParser
{
    public $fileName;
    public function __construct($fileName){
        $this->fileName = $fileName;
    }
    public function getFileContent(){
        return file_get_contents(app_path($this->fileName));
    }
    public function toArray(){
        return json_decode($this->getFileContent(),true);
    }
}
