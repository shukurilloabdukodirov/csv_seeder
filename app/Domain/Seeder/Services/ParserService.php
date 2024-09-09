<?php

namespace App\Domain\Seeder\Services;

use App\Models\Product;
use App\Utils\CsvParser;
use Carbon\Carbon;
use Exception;


/**
 * The ParserService class is responsible for parsing CSV data, preparing products for import.
 * Importing products into the database.
 * It also validates the product data based on stock levels, price range, and encoding.
 */
class ParserService
{
    public CsvParser $parser;
    public array $products = [];

    public array $successProducts = [];
    public array $needsToCheckEncoding = ['Product Name','Product Description'];

    public array $failedProducts = [];
    public float $usdRate = 1;

    public int $minPriceUsd = 5;
    public int $maxPriceUsd = 1000;
    public int $minStock = 10;

    public string $finalMessage = '';
    public function __construct(CsvParser $parser){
        $this->parser = $parser;
    }

    /**
     * @param array $products
     */
    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }
    /**
     * @return array
     */
    public function getFailedProducts(): array
    {
        return $this->failedProducts;
    }

    /**
     * @param array $failedProducts
     */
    public function setFailedProducts(array $failedProducts): void
    {
        $this->failedProducts[] = $failedProducts;
    }

    public function setSuccessProducts(array $successProducts): void
    {
        $this->successProducts[] = $successProducts;
    }

    public function getSuccessProducts(): array{
        return $this->successProducts;
    }

    public function setFinalMessage(string $finalMessage): void{
        $this->finalMessage = $finalMessage;
    }
    public function getFinalMessage(): string{
        return $this->finalMessage;
    }
    /**
     * @throws Exception
     */
    public function parseData():void
    {
        $this->parser->parse();
        $this->setProducts($this->parser->getRawData());
    }

    /**
     * @throws Exception
     */
    public function importProducts($isTestMode=false): bool
    {
        try {
            if (!$isTestMode) {
                foreach($this->getSuccessProducts() as $product){
                    $data['strProductName'] = $product['Product Name'];
                    $data['strProductDesc'] = $product['Product Description'];
                    $data['strProductCode'] = $product['Product Code'];
                    $data['stock'] = $product['Stock'];
                    $data['price'] = $product['Cost in GBP'];
                    $data['dtmAdded'] = Carbon::now();
                    $data['dtmDiscontinued'] = $product['dtmDiscontinued'];
                    Product::updateOrCreate($data,['strProductCode'=>$product['Product Code']]);
                }
            };
            $this->setFinalMessage("Successfully imported products: "
                .count($this->getSuccessProducts())
                ." Failed products count: ". count($this->getFailedProducts()+$this->parser->getFailedRows()));
            return true;
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }


    }

    public function prepareProducts():void
    {
        foreach ($this->products as $product) {
            if(!$this->isCorrectPrice($product)||!$this->isCorrectStock($product)){
                $this->setFailedProducts($product);
            }
            else{
                $product['dtmDiscontinued'] = $this->isDiscontinued($product)?Carbon::now():null;
                $this->setSuccessProducts($this->checkEncoding($product));
            }
        }
    }

    public function isCorrectPrice($product):bool{
        $priceInGbp = $product['Cost in GBP']??null;

        if(is_null($priceInGbp)){
            return false;
        }
        $priceInUsd = (float)preg_match('/\$/',$priceInGbp)?$priceInGbp:$this->convertGbpToUSD($priceInGbp);
        if($priceInUsd<$this->minPriceUsd){
            return false;
        }
        if($priceInUsd>$this->maxPriceUsd){
            return false;
        }
        return true;
    }

    public function isDiscontinued($product):bool{
        if(isset($product['Discontinued'])){
            return $product['Discontinued']==='yes';
        }
        return false;

    }

    public function isCorrectStock($product):bool{
        if(isset($product['Stock'])){
            return $product['Stock']>=$this->minStock;
        }
        return false;
    }

    public function checkEncoding($product):array{
        foreach($this->needsToCheckEncoding as $needToCheck){
            if(array_key_exists($needToCheck,$product)&&!$this->isCorrectEncoding($product[$needToCheck])){
                $product[$needToCheck] = $this->fixEncoding($product[$needToCheck]);
            }
        }
        return $product;
    }

    public function detectEncoding($data):string
    {
        return  mb_detect_encoding($data);
    }
    public function isCorrectEncoding($data):bool{
        return  $this->detectEncoding($data)==='UTF-8';
    }

    public function fixEncoding($data):mixed{
        return mb_convert_encoding($data, 'UTF-8', $this->detectEncoding($data));
    }

    public function convertGbpToUSD($gbp):float{
        return $gbp*$this->usdRate;
    }
}
