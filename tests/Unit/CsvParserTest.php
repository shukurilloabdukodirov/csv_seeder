<?php

namespace Tests\Unit;
use App\Utils\CsvParser;
use PHPUnit\Framework\TestCase;

class CsvParserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    protected string|false $csvFile;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a temporary CSV file for testing
        $this->csvFile = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($this->csvFile, "Product Code,Product Name,Cost\nP001,Item1,10\nP002,Item2,20");
    }

    protected function tearDown(): void
    {
        // Delete the temporary file after each test
        @unlink($this->csvFile);
        parent::tearDown();
    }

    public function testParseCsvFile(): void
    {
        $parser = new CsvParser($this->csvFile);
        $parser->parse();

        $rawData = $parser->getRawData();

        $this->assertIsArray($rawData);
        $this->assertCount(2, $rawData); // There are 2 rows in the CSV
        $this->assertSame('Item1', $rawData[0]['Product Name']);
    }

    public function testSetAndGetRawData(): void
    {
        $parser = new CsvParser($this->csvFile);
        $data = [
            ['Product Code' => 'P001', 'Product Name' => 'Item1', 'Cost' => '10'],
            ['Product Code' => 'P002', 'Product Name' => 'Item2', 'Cost' => '20']
        ];

        $parser->setRawData($data);
        $this->assertSame($data, $parser->getRawData());
    }

    public function testSetAndGetFailedRows(): void
    {
        $parser = new CsvParser($this->csvFile);
        $failedRow = ['Product Code' => 'P001', 'Product Name' => 'Item1'];

        $parser->setFailedRows($failedRow);
        $this->assertContains($failedRow, $parser->getFailedRows());
    }

    public function testParseWithMissingFile(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("File not found.");

        $parser = new CsvParser('/nonexistent/file.csv');
        $parser->parse();
    }
}
