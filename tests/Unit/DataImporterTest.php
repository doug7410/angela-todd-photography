<?php

namespace Tests;

use Tests\TestCase;
use App\Jobs\ImportImage;
use App\Services\DataImporter;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataImporterTest extends TestCase
{
  use RefreshDatabase;
  private $sampleFile;

  public function setUp() {
    parent::setUp();
    $this->sampleFile = realpath(__DIR__ . '/../sampleData.csv');
  }

  /**
   * @test
   */
  public function hasReferenceToTheFilePath()
  {
    $importer = new DataImporter($this->sampleFile);

    $this->assertEquals($this->sampleFile, $importer->filePath);
  }

  /**
   * @test
   */
  public function createsCategoryForEachCategoryInTheFile() {
    $importer = new DataImporter($this->sampleFile);
    $this->assertInstanceOf(DataImporter::class, $importer);

    $importer->createCategories();
//
//    $this->assertDatabaseHas('categories', ['name' => 'landscape', 'sort_order' => 1]);
//    $this->assertDatabaseHas('categories', ['name' => 'portrait', 'sort_order' => 2]);
//    $this->assertDatabaseHas('categories', ['name' => 'black and white', 'sort_order' => 3]);
//    $this->assertDatabaseHas('categories', ['name' => 'HDR', 'sort_order' => 4]);
//    $this->assertDatabaseHas('categories', ['name' => 'Still Life', 'sort_order' => 5]);
  }

  /**
   * @test
   */
  public function createdAQueueJobForEachImage()
  {
    Queue::fake();
    $importer = new DataImporter($this->sampleFile);
    $importer->dispatchImportImageJobs();

    Queue::assertPushed(ImportImage::class, 4);
    Queue::assertPushed(ImportImage::class, function ($job) {
      return $job->image == [
        'file' => 'blackbeach.jpg',
        'caption' => 'Black Sand beach in Iceland',
        'categories' => [
          ['name' => 'black and white', 'sort_order' => 1]
        ],
        'slider' => 1
      ];
    });
    Queue::assertPushed(ImportImage::class, function ($job) {
      return $job->image == [
          'file' => 'valley.jpg',
          'caption' => 'Tunnel View',
          'categories' => [
            ['name' => 'black and white', 'sort_order' => 2]
          ],
          'slider' => 3
        ];
    });
  }

  /**
   * @test
   */
  public function parses_a_line_to_the_correct_format_for_the_ImportImage_job()
  {
    $importer = new DataImporter($this->sampleFile);
    $res = $importer->parseLine('123.jpg,Some cool places,1,1,,,,1');

    $this->assertArraySubset([
      'file' => '123.jpg',
      'caption' => 'Some cool places',
      'categories' => [
        ['name' => 'landscape', 'sort_order' => 1],
        ['name' => 'portrait', 'sort_order' => 1]
      ],
      'slider' => 1
    ], $res);
  }
}
