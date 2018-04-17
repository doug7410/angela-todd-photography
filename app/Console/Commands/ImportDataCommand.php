<?php

namespace App\Console\Commands;

use App\Services\CsvParser;
use App\Services\DataImporter;
use Illuminate\Console\Command;

class ImportDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import images, categories, and slider data to website from CSV file. ';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = new CsvParser(config('dataImporter.csvPath'));
        $importer = new DataImporter($data);
        $importer->createCategories();
        $importer->dispatchImportImageJobs();
    }
}
