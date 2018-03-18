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

class ImportImage implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $image;
  public $cloudinaryService;
  public $imagesFolder;

  public function __construct($image, $cloudinaryService = null)
  {
    $this->image = $image;
    $this->cloudinaryService = $cloudinaryService ? $cloudinaryService : new Uploader();
    $this->imagesFolder = __DIR__ . '/../../../For the Website/';
  }

  public function handle()
  {
    Log::info('Importing Image '. $this->image['file']. '...');
    
    $imageUpload = $this->cloudinaryService->upload(
      realpath($this->imagesFolder . $this->image['file']),
      config('cloudinary.upload')
    );

    $image = Image::create([
      'path' => $imageUpload['url'],
      'caption' => $this->image['caption'],
      'meta_data' => json_encode($imageUpload['image_metadata'])
    ]);

    foreach ($this->image['categories'] as $imageCategory) {
      $category = Category::where('name', $imageCategory['name'])->first();
      CategoryImage::create([
        'category_id' => $category->id,
        'image_id' => $image->id,
        'category_sort_order' => $imageCategory['sort_order']
      ]);

      if($imageCategory['sort_order'] == 1) {
        $category->image_id = $image->id;
        $category->save();
      }
    }

    if($this->image['slider']) {
      Slide::create([
        'image_id' => $image->id,
        'sort_order' => $this->image['slider']
      ]);
    }

    Log::info('ImportImage successfully finished for: '. $imageUpload['url']);
  }
}
