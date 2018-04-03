<?php

namespace App\Jobs;

use App\CategoryImage;
use App\Image;
use App\Slide;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ImportImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $image;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($image)
    {
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Adding image ' . $this->image['image_name'] . ' to database.');
        $image = Image::create([
            'image_name' => $this->image['image_name'],
            'path' => $this->image['path'],
            'caption' => $this->image['caption'],
            'meta_data' => json_encode($this->image['meta_data'])
        ]);

        foreach ($this->image['categories'] as $category) {
            Log::info('Adding image ' . $this->image['image_name'] . ' to category id ' . $category['id']);

            CategoryImage::create([
                'category_id' => $category['id'],
                'image_id' => $image->id,
                'category_sort_order' => $category['sort_order']
            ]);
        }

        if ($this->image['slider'] > 0) {
            Log::info('Adding image ' . $this->image['image_name'] . ' to slides with sort order ' . $this->image['slider']);
            Slide::create([
                'image_id' => $image->id,
                'sort_order' => $this->image['slider']
            ]);
        }

        Log::info($this->image['image_name'] . ' has been added to the database');
    }
}
