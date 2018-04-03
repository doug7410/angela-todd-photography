<?php

namespace App\Services;

use App\Category;
use App\Jobs\UploadImageJob;
use Illuminate\Support\Facades\Log;

class DataImporter
{

  public $filePath;
  public $categories;

  /**
   * DataImporter constructor.
   * @param string $filePath
   */
  public function __construct($filePath)
  {
    $this->filePath = $filePath;
    $this->categories = $this->parseCategoryData();
  }

  /**
   *
   */
  public function createCategories()
  {
    $categories = Category::all()->pluck('name')->toArray();

    foreach ($this->categories as $index => $category) {
      if (!in_array($category['name'], $categories)) {
        Category::create([
          'name' => $category['name'],
          'description' => $category['description'],
          'sort_order' => $index + 1
        ]);
        Log::info('created category ' . $category['name']);
      }
    }
  }

  /**
   *
   */
  public function dispatchImportImageJobs()
  {
    foreach (explode("\n", file_get_contents($this->filePath)) as $index => $line) {
      if ($index > 1) {
        UploadImageJob::dispatch($this->parseLine($line));
        Log::info('dispatched ImportImage job for "' . $line . '"');
      }
    }
  }

  /**
   * @param $line
   * @return array
   */
  public function parseLine($line)
  {
    $lineArr = explode(',', $line);
    $categoryArray = [];
    $categoryIndex = 2;

    foreach ($this->categories as $category) {
      if (!!$lineArr[$categoryIndex]) {
        $categoryArray[] = ['name' => $category['name'], 'sort_order' => $lineArr[$categoryIndex]];
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

  /**
   *
   */
  private function parseCategoryData()
  {
    $firstLine = explode(',', explode("\n", file_get_contents($this->filePath))[0]);
    $secondLine = explode(',', explode("\n", file_get_contents($this->filePath))[1]);
    $categoryNames = array_slice($firstLine, 2, -1);
    $categoryDescriptions = array_slice($secondLine, 2, -1);
    $categories = [];
    foreach ($categoryNames as $index => $name) {
      $categories[] = ['name' => $name, 'description' => $categoryDescriptions[$index]];
    }

    return $categories;
  }
}