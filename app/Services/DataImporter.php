<?php

namespace App\Services;

use App\Category;
use App\Jobs\ImportImage;
use Illuminate\Support\Facades\Log;

class DataImporter {

  public $filePath;
  public $categories;

  /**
   * DataImporter constructor.
   * @param string $filePath
   */
  public function __construct($filePath)
  {
    $this->filePath = $filePath;
    $firstLine = explode(',',explode("\n", file_get_contents($this->filePath))[0]);
    $this->categories = array_slice($firstLine, 2, -1);
  }

  /**
   *
   */
  public function createCategories() {
    foreach ($this->categories as $index => $name) {
      Category::create(['name' => $name, 'sort_order' => $index + 1]);
      Log::info('created category '. $name);
    }
  }

  /**
   *
   */
  public function dispatchImportImageJobs() {
    foreach (explode("\n", file_get_contents($this->filePath)) as $index => $line) {
      if ($index > 0) {
        ImportImage::dispatch($this->parseLine($line));
        Log::info('dispatched ImportImage job for "'. $line. '"');
      }
    }
  }

  /**
   * @param $line
   * @return array
   */
  public function parseLine($line) {
    $lineArr = explode(',', $line);
    $categoryArray = [];
    $categoryIndex = 2;

    foreach ($this->categories as $category) {
      if(!!$lineArr[$categoryIndex]){
        $categoryArray[] = ['name' => $category, 'sort_order' => $lineArr[$categoryIndex]];
      }
      $categoryIndex++;
    }

    return [
      'file' => $lineArr[0],
      'caption' => $lineArr[1],
      'categories' => $categoryArray,
      'slider' => last($lineArr)
    ];
  }


}