<?php

namespace Tests\Integration;

use App\Category;
use App\Services\CsvParser;
use Tests\TestCase;
use App\Jobs\UploadImageJob;
use App\Services\DataImporter;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CsvParserTest extends TestCase
{
    use RefreshDatabase;
    private $sampleFile;

    public function setUp()
    {
        parent::setUp();
        $this->sampleFile = realpath(__DIR__ . '/../../sampleData.csv');
    }


    /**
     * @test
     */
    public function parses_a_line_to_the_correct_format_for_the_ImportImage_job()
    {
        $parser = new CsvParser($this->sampleFile);
        $data = $parser->dataForUploadImageJobs();
        $imageOne = [
            'file' => 'blackbeach.jpg',
            'caption' => 'Black Sand beach in Iceland',
            'categories' => [
                ['name' => 'landscape', 'sort_order' => 2],
                ['name' => 'black and white', 'sort_order' => 1]
            ],
            'slider' => 1
        ];

        $imageTwo = [
            'file' => 'CraterLake.jpg',
            'caption' => 'Crater Lake',
            'categories' => [
                ['name' => 'landscape', 'sort_order' => 1],
            ],
            'slider' => 2
        ];

        $this->assertEquals($imageOne, $data[0], "\$canonicalize = true");
        $this->assertEquals($imageTwo, $data[1], "\$canonicalize = true");
    }

    /**
     * @test
     */

    public function parsedCategoriesFromCsvFile()
    {
        $parser = new CsvParser($this->sampleFile);

        $this->assertArraySubset([
            ['name' => 'landscape', 'description' => 'Landscape Photos'],
            ['name' => 'portrait', 'description' => 'Some Great Portraits'],
            ['name' => 'black and white', 'description' => 'It\'s all about the lighting'],
            ['name' => 'HDR', 'description' => 'Super High Def'],
            ['name' => 'Still Life', 'description' => 'Be still']
        ], $parser->categoryData());
    }
}
