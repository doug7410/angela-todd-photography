<?php

namespace App\Jobs;

use App\Category;
use App\CategoryImage;
use App\Image;
use App\Slide;
use Cloudinary;
use Cloudinary\Uploader;
use Illuminate\Bus\Queueable;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $image;
    public $cloudinaryService;

    public function __construct($image, $cloudinaryService = null)
    {
        $this->image = $image;
        $this->cloudinaryService = $cloudinaryService ? $cloudinaryService : new Uploader();
    }

    public function handle()
    {
        Log::info('Importing Image ' . $this->image['file'] . '...');

        if (Image::where('image_name', $this->image['file'])->first()) {
            Log::info('Image with name ' . $this->image['file'] . ' has already been uploaded');
            return;
        }

        $categories = collect($this->image['categories'])->map(function($category){
            $id = Category::where('name', $category['name'])->first()->id;
            return ['id' => $id, 'sort_order' => $category['sort_order']];
        })->toArray();

        $imageUpload = $this->cloudinaryService->upload(
            config('cloudinary.from_folder') . $this->image['file'],
            config('cloudinary.upload')
        );

        ImportImageJob::dispatch([
            'path' => $imageUpload['url'],
            'image_name' => $this->image['file'],
            'caption' => $this->image['caption'],
            'meta_data' => $imageUpload['image_metadata'],
            'categories' => $categories,
            'slider' => $this->image['slider']
        ]);

        Log::info('ImportImage successfully finished for: ' . $this->image['file']);
    }
}
