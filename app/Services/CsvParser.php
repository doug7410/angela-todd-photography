<?php


namespace App\Services;


class CsvParser implements DataParserInterface
{
    private $filePath;


    /**
     * CsvParser constructor.
     * @param $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function categoryData(): array
    {
        $categories = [];

        $firstLine = explode(',', explode("\n", file_get_contents($this->filePath))[0]);
        $secondLine = explode(',', explode("\n", file_get_contents($this->filePath))[1]);
        $categoryNames = array_slice($firstLine, 2, -1);
        $categoryDescriptions = array_slice($secondLine, 2, -1);
        foreach ($categoryNames as $index => $name) {
            $categories[] = ['name' => $name, 'description' => $categoryDescriptions[$index]];
        }

        return $categories;
    }

    public function dataForUploadImageJobs(): array
    {
        $parsedLines = [];

        foreach (explode("\n", file_get_contents($this->filePath)) as $index => $line) {
            if ($index > 1) {
                $lineArr = str_getcsv($line);
                $categoryArray = [];
                $categoryIndex = 2;

                foreach ($this->categoryData() as $category) {
                    if (!!$lineArr[$categoryIndex]) {
                        $categoryArray[] = ['name' => $category['name'], 'sort_order' => $lineArr[$categoryIndex]];
                    }
                    $categoryIndex++;
                }

                $parsedLines[] =  [
                    'file' => $lineArr[0],
                    'caption' => $lineArr[1],
                    'categories' => $categoryArray,
                    'slider' => last($lineArr)
                ];
            }
        }

        return $parsedLines;
    }
}