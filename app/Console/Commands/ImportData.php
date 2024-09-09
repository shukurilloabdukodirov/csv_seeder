<?php

namespace App\Console\Commands;

use App\Domain\Seeder\Services\ParserService;
use App\Utils\CsvParser;
use Exception;
use Illuminate\Console\Command;

/**
 * The ImportData class is responsible for handling console commands for importing data from csv file.
 */
class ImportData extends Command
{
    protected ParserService $parserService;
    public function __construct()
    {
        parent::__construct();
        $this->parserService = new ParserService(new CsvParser(app_path('Domain/Seeder/Data/stock.csv')));
    }
    protected $signature = 'import:stock { mode? }';
    protected $description = 'Import stock from a CSV file';


    /**
     * Execute the console command.
     *
     * @return bool
     * @throws Exception
     */
    public function handle(): bool
    {
        $this->parserService->parseData();
        $this->parserService->prepareProducts();

        $this->parserService->importProducts($this->argument('mode')==='test');
        $this->info($this->parserService->getFinalMessage());
        $this->newLine(1);
        $this->question('Successfully imported stock data are following:');
        $this->table($this->parserService->parser->getHeaders(),$this->parserService->getSuccessProducts());
        $this->error('Failed products are following:');
        $this->table($this->parserService->parser->getHeaders(),$this->parserService->getFailedProducts()+$this->parserService->parser->getFailedRows());
        $this->newLine(2);

        return Command::SUCCESS;
    }
}
