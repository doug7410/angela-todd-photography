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

class UploadImageJobTest extends TestCase
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
            'caption' => 'Some cool place',
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
    public function uploads_image_to_cloudinary_and_creates_queue_jobs()
    {
        Queue::fake();

        $imagePath = config('cloudinary.from_folder') . $this->image['file'];
        $cloudinaryService = m::mock('cloudinaryService');
        $cloudinaryService->shouldReceive('upload')->withArgs([$imagePath, config('cloudinary.upload')])->andReturn(
            [
                'url' => 'http://foo.com/image.jpg',
                'image_metadata' => [
                    'CodedCharacterSet' => 'UTF8',
                    'ApplicationRecordVersion' => '4',
                ]
            ])->once();

        $importer = new UploadImageJob($this->image, $cloudinaryService);
        $importer->handle();

        Queue::assertPushed(ImportImageJob::class, function ($job) {
            return $job->image === [
                    'path' => 'http://foo.com/image.jpg',
                    'image_name' => '123.jpg',
                    'caption' => 'Some cool place',
                    'meta_data' => [
                        'CodedCharacterSet' => 'UTF8',
                        'ApplicationRecordVersion' => '4',
                    ],
                    'categories' => [
                        ['id' => Category::where('name', 'landscape')->first()->id, 'sort_order' => 1],
                        ['id' => Category::where('name', 'portrait')->first()->id, 'sort_order' => 2]
                    ],
                    'slider' => 1
                ];
        });

    }

    /**
     * @test
     */
    public function does_not_upload_image_if_it_exists()
    {
        factory(Image::class)->create(['image_name' => '123.jpg']);

        $cloudinaryService = m::mock('cloudinaryService');
        $cloudinaryService->shouldNotHaveReceived('upload');

        $importer = new UploadImageJob($this->image, $cloudinaryService);
        $importer->handle();
    }

}