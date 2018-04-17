<?php

namespace App\Services;

use App\Category;
use App\Jobs\UploadImageJob;
use Illuminate\Support\Facades\Log;

class DataImporter
{

    private $data;

    public function __construct(DataParserInterface $data)
    {
        $this->data = $data;
    }

    /**
     *
     */
    public function createCategories()
    {
        $categories = Category::all()->pluck('name')->toArray();

        foreach ($this->data->categoryData() as $index => $category) {
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
        foreach ($this->data->dataForUploadImageJobs() as $imageData) {
            try {
                UploadImageJob::dispatch($imageData);
                Log::info('dispatched ImportImage job for ' . json_encode($imageData));
            } catch (\Exception $e) {
                Log::info('error dispatching ImportImage job for ' . json_encode($imageData));
                Log::info($e);
            }
        }
    }
}