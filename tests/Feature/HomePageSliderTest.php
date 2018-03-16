<?php

namespace Tests\Feature;

use App\Image;
use App\Slide;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomePageSliderTest extends TestCase
{
  use RefreshDatabase;

  /**
   * @test
   */
  public function hopePageShowsAllTheSlidersInTheCorrectOrder()
  {
    $img1 = factory(Image::class)->create(['id' => 1, 'path' => 'foo.jpg']);
    $img2 = factory(Image::class)->create(['id' => 2, 'path' => 'bar.jpg']);

    factory(Slide::class)->create(['image_id' => $img1->id, 'sort_order' => 2]);
    factory(Slide::class)->create(['image_id' => $img2->id, 'sort_order' => 1]);

    $response = $this->get('/');

    $slides = $response->original->getData()['slides']->toArray();

    $this->assertEquals([
      [
        'id' => 2,
        'path' =>'bar.jpg'
      ],
      [
        'id' => 1,
        'path' => 'foo.jpg'
      ]
    ], $slides);
  }
}
