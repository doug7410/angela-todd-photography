<?php

namespace Tests\Unit\Config;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CloudinaryConfigTest extends TestCase
{
//  use RefreshDatabase;

    /**
     * @test
     */
    public function upload_config_is_correct()
    {
        $config = [
            'width' => '1200',
            'quality' => '85',
            'image_metadata' => true,
            'eager' => [
                [
                    'width' => '250',
                    'crop' => 'scale'
                ],
                [
                    'width' => '500',
                    'crop' => 'scale'
                ]
            ]
        ];

        $this->assertEquals($config, config('cloudinary.upload'));
    }

    /**
     * @test
     */
    public function from_image_folder_is_correct() {
        $imagesFolder = realpath(__DIR__ . '/../../../../For the Website/').'/';
        $this->assertEquals($imagesFolder, config('cloudinary.from_folder'));

    }

}
