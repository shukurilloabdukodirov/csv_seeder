<?php

namespace Tests\Unit;

use App\Domain\Seeder\Services\ParserService;
use App\Utils\CsvParser;
use Exception;
use PHPUnit\Framework\TestCase;
use Mockery;
/**
 * The ParserServiceTest class is responsible for testing ParserService class methods.
 */
class ParserServiceTest extends TestCase
{

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testPrepareProductsValidProduct(): void
    {
        // Mock CsvParser and simulate raw data
        $mockParser = Mockery::mock(CsvParser::class);
        $mockParser->shouldReceive('getRawData')->andReturn([
            ['Product Code' => 'P001', 'Product Name' => 'Test Product', 'Cost in GBP' => 100, 'Stock' => 50]
        ]);

        $service = new ParserService($mockParser);
        $service->setProducts($mockParser->getRawData());
        $service->prepareProducts();

        $this->assertCount(1, $service->getSuccessProducts());
    }

    public function testPrepareProductsInvalidPrice(): void
    {
        $mockParser = Mockery::mock(CsvParser::class);
        $mockParser->shouldReceive('getRawData')->andReturn([
            ['Product Code' => 'P002', 'Product Name' => 'Test Product', 'Cost in GBP' => 3, 'Stock' => 50]
        ]);

        $service = new ParserService($mockParser);
        $service->setProducts($mockParser->getRawData());
        $service->prepareProducts();

        $this->assertCount(1, $service->getFailedProducts());
    }

    public function testPriceConversion(): void
    {
        $mockParser = Mockery::mock(CsvParser::class);
        $service = new ParserService($mockParser);

        $service->usdRate = 1.3; // Mock the USD rate
        $convertedPrice = $service->convertGbpToUSD(100); // Convert 100 GBP to USD

        $this->assertEquals(130, $convertedPrice);
    }

    /**
     * @throws Exception
     */
    public function testImportProductsInTestMode(): void
    {
        $mockParser = Mockery::mock(CsvParser::class);
        $mockParser->shouldReceive('getFailedRows')->andReturn([]);
        $service = new ParserService($mockParser);

        $productData = [
            ['Product Code' => 'P001', 'Product Name' => 'Test Product', 'Cost in GBP' => 100, 'Stock' => 50]
        ];
        $service->setSuccessProducts($productData);

        $result = $service->importProducts(true);
        $this->assertTrue($result);
        $this->assertEquals('Successfully imported products: 1 Failed products count: 0', $service->getFinalMessage());
    }


}
