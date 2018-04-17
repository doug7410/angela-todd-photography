<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Jobs\UploadImageJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportDataCommandTest  extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        config(['dataImporter.csvPath' => __DIR__.'/../../../sampleData.csv']);
    }

    /**
     * @test
     */
    public function itCreatesCategoriesFromTheCsvProvided()
    {
        Artisan::call('import:data');
        $this->assertDatabaseHas('categories', ['name' => 'landscape', 'description' => 'Landscape Photos', 'sort_order' => 1]);
        $this->assertDatabaseHas('categories', ['name' => 'portrait', 'description' => 'Some Great Portraits','sort_order' => 2]);
        $this->assertDatabaseHas('categories', ['name' => 'black and white', 'description' => 'It\'s all about the lighting','sort_order' => 3]);
        $this->assertDatabaseHas('categories', ['name' => 'HDR', 'description' => 'Super High Def','sort_order' => 4]);
        $this->assertDatabaseHas('categories', ['name' => 'Still Life', 'description' => 'Be still','sort_order' => 5]);
    }

    /**
     * @test
     */
    public function itDispatchesQueueJobsForEachImage()
    {
        Queue::fake();
        Artisan::call('import:data');
        Queue::assertPushed(UploadImageJob::class, 4);
    }
}
