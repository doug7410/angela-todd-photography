<?php

namespace App\Console\Commands;

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $importer = new DataImporter(__DIR__.'/importData.csv');
      $importer->createCategories();
      $importer->dispatchImportImageJobs();
    }
}
