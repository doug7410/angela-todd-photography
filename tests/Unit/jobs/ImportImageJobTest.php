<?php

namespace Tests\Unit\Jobs;

use App\Category;
use App\Image;
use App\Jobs\ImportImageJob;
use Illuminate\Support\Facades\Queue;
use Mockery as m;
use App\Jobs\UploadImageJob;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\BinaryOp\Mod;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ImportImageJobTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use RefreshDatabase;

    private $image;
    private $categories;

    public function setUp()
    {
        parent::setUp();
        $this->image = [
            'path' => 'http://foo.com/image.jpg',
            'image_name' => '123.jpg',
            'caption' => 'Some cool place',
            'meta_data' => [
                'CodedCharacterSet' => 'UTF8',
                'ApplicationRecordVersion' => '4',
            ],
            'categories' => [
                ['id' => 1, 'sort_order' => 2],
                ['id' => 2, 'sort_order' => 1]
            ],
            'slider' => 1
        ];

        $this->categories = [
            'landscape' => factory(Category::class)->create(['id' => 1, 'name' => 'landscape', 'image_id' => null]),
            'portrait' => factory(Category::class)->create(['id' => 2, 'name' => 'portrait', 'image_id' => null])
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
    public function adds_image_to_database()
    {
       $importImageJob = new ImportImageJob($this->image);
       $importImageJob->handle();

       $this->assertDatabaseHas('images', [
           'image_name' => $this->image['image_name'],
           'path' => $this->image['path'],
           'caption' => $this->image['caption'],
           'meta_data' => json_encode($this->image['meta_data'])
       ]);
    }

    /**
     * @test
     */
    public function creates_the_relationship_to_categories() {
        $importImageJob = new ImportImageJob($this->image);
        $importImageJob->handle();

        $imageId = Image::where('image_name', '123.jpg')->first()->id;

        $this->assertDatabaseHas('category_image', [
            'category_id' => 1,
            'image_id' => $imageId,
            'category_sort_order' => 2
        ]);
        $this->assertDatabaseHas('category_image', [
            'category_id' => 2,
            'image_id' => $imageId,
            'category_sort_order' => 1
        ]);
        $this->assertDatabaseHas('slides', [
            'image_id' => $imageId,
            'sort_order' => 1
        ]);
    }
}