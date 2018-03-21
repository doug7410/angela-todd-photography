<?php

namespace Tests\Unit\Jobs;

use App\Category;
use App\Image;
use Mockery as m;
use App\Jobs\ImportImage;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\BinaryOp\Mod;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ImportImageTest extends TestCase
{
  use MockeryPHPUnitIntegration;
  use RefreshDatabase;

  private $image;
  private $categories;

  public function setUp()
  {
    parent::setUp();
    $this->image = [
      'file' => '123.jpg',
      'caption' => 'Some cool places',
      'categories' => [
        ['name' => 'landscape', 'sort_order' => 1],
        ['name' => 'portrait', 'sort_order' => 2]
      ],
      'slider' => 1
    ];
    $this->categories = [
      'landscape' => factory(Category::class)->create(['name' => 'landscape', 'image_id' => null]),
      'portrait' => factory(Category::class)->create(['name' => 'portrait', 'image_id' => null])
    ];
  }

  public function tearDown()
  {
    parent::tearDown();
    m::close();
  }

  /**
   * @test
   */
  public function uploads_image_to_cloudinary_and_creates_associated_database_records()
  {
    $imagesFolder = __DIR__ . '/../../../../For the Website/';

    $imagePath = realpath($imagesFolder . $this->image['file']);
    $cloudinaryService = m::mock('cloudinaryService');
    $cloudinaryService->shouldReceive('upload')->withArgs([$imagePath, config('cloudinary.upload')])->andReturn(
      [
        'url' => 'http://res.cloudinary.com/dsteinberg/image/upload/v1521386006/oihqt9g9e7ocanpbhocy.jpg',
        'image_metadata' => [
          'CodedCharacterSet' => 'UTF8',
          'ApplicationRecordVersion' => '4',
          'TimeCreated' => '13:04:31+00:00',
          'DigitalCreationDate' => '2015:08:12',
          'PhotometricInterpretation' => 'RGB'
        ]
      ])->once();

    $importer = new ImportImage($this->image, $cloudinaryService);
    $importer->handle();

    $this->assertDatabaseHas('images', [
      'image_name' => '123.jpg',
      'path' => 'http://res.cloudinary.com/dsteinberg/image/upload/v1521386006/oihqt9g9e7ocanpbhocy.jpg',
      'caption' => 'Some cool places',
      'meta_data' => json_encode([
        'CodedCharacterSet' => 'UTF8',
        'ApplicationRecordVersion' => '4',
        'TimeCreated' => '13:04:31+00:00',
        'DigitalCreationDate' => '2015:08:12',
        'PhotometricInterpretation' => 'RGB'
      ])
    ]);

    $image = Image::where(['path' => 'http://res.cloudinary.com/dsteinberg/image/upload/v1521386006/oihqt9g9e7ocanpbhocy.jpg'])->first();

    $this->assertDatabaseHas('category_image', [
      'category_id' => $this->categories['landscape']->id,
      'image_id' => $image->id,
      'category_sort_order' => 1
    ]);

    $this->assertDatabaseHas('category_image', [
      'category_id' => $this->categories['portrait']->id,
      'image_id' => $image->id,
      'category_sort_order' => 2
    ]);

    $this->assertDatabaseHas('categories', ['name' => 'landscape', 'image_id' => $image->id]);
    $this->assertDatabaseHas('categories', ['name' => 'portrait', 'image_id' => null]);

    $this->assertDatabaseHas('slides', [
      'image_id' => $image->id,
      'sort_order' => 1
    ]);
  }

  /**
   * @test
   */
  public function updates_existing_image_data_and_does_not_upload()
  {
    factory(Image::class)->create([
      'image_name' => '123.jpg',
      'path' => '/some/foo/image.jpg',
      'caption' => 'Some cool places 1234',
      'meta_data' => json_encode([
        'CodedCharacterSet' => 'UTF8',
        'ApplicationRecordVersion' => '4',
        'TimeCreated' => '13:04:31+00:00',
        'DigitalCreationDate' => '2015:08:12',
        'PhotometricInterpretation' => 'RGB'
      ])
    ]);

    $cloudinaryService = m::mock('cloudinaryService');
    $cloudinaryService->shouldNotHaveReceived('upload');

    $importer = new ImportImage($this->image, $cloudinaryService);
    $importer->handle();

    $this->assertDatabaseHas('images', [
      'image_name' => '123.jpg',
      'path' => '/some/foo/image.jpg',
      'caption' => 'Some cool places',
      'meta_data' => json_encode([
        'CodedCharacterSet' => 'UTF8',
        'ApplicationRecordVersion' => '4',
        'TimeCreated' => '13:04:31+00:00',
        'DigitalCreationDate' => '2015:08:12',
        'PhotometricInterpretation' => 'RGB'
      ])
    ]);
  }

  /**
   * @test
   */
  public function deletes_existing_image_record_if_not()
  {

  }
}