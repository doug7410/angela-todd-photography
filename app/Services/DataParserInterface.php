<?php


namespace App\Services;


interface DataParserInterface
{
    public function categoryData(): array;

    public function dataForUploadImageJobs(): array;
}