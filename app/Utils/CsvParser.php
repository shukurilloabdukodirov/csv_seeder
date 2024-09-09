<?php

namespace App\Utils;

class CsvParser
{
    public string $filePath = '';
    public array $rawData = [];

    public array $headers = [];
    public array $failedRows = [];
    public function __construct($filePath){
        $this->filePath = $filePath;
    }

    public function setRawData(array $data):void{
        $this->rawData = $data;
    }
    public function getRawData(): array{
        return $this->rawData;
    }

    public function setFailedRows(array $rows):void{
        $this->failedRows[] = $rows;
    }
    public function getFailedRows(): array{
        return $this->failedRows;
    }

    public function setHeaders(array $headers):void{
        $this->headers = $headers;
    }

    public function getHeaders(): array{
        return $this->headers;
    }

    /**
     * @throws \Exception
     */
    public function parse():void
    {
        $data = [];

        if (!file_exists($this->filePath)) {
            throw new \Exception("File not found.");
        }

        if (($handle = fopen($this->filePath, 'r')) !== false) {
            // Read header row
            $header = fgetcsv($handle, 1000, ",");
            $this->setHeaders($header);
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                if (count($header) !== count($row)) {
                    $row = array_slice($row, 0, count($header),true);
                    $row = array_pad($row, count($header), null);
                    $row = array_combine($header, $row);
                    $this->setFailedRows($row);
                }else{
                    $data[] = array_combine($header, $row);
                }
            }

            fclose($handle);
        }

        $this->setRawData($data);
    }

}
