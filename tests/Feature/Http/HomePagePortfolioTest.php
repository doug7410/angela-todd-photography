<?php

namespace Tests\Feature;

use App\Category;
use App\CategoryImage;
use App\Image;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomePagePortfolioTest extends TestCase
{
  use RefreshDatabase;

  /**
   * @test
   * @return void
   */
  public function homePageShowsAllCategoriesThatHaveImages()
  {
    $cat1Image = factory(Image::class)->create();
    $cat2Image = factory(Image::class)->create();
    $cat1 = factory(Category::class)->create(['name' => 'cat1', 'image_id' => $cat1Image->id]);
    $cat2 = factory(Category::class)->create(['name' => 'cat2', 'image_id' => $cat2Image->id]);
    $cat1->images()->sync([$cat1Image->id => ['category_sort_order' => 1]]);
    $cat2->images()->sync([$cat2Image->id => ['category_sort_order' => 1]]);
    $cat3 = factory(Category::class)->create(['name' => 'cat3']);

    $response = $this->get('/');

    $response->assertSee('/category/' . $cat1->id);
    $response->assertSee($cat1->image->path);
    $response->assertSee($cat1->name);
    $response->assertSee($cat2->description);
    $response->assertSee('/category/' . $cat2->id);
    $response->assertSee($cat2->image->path);
    $response->assertSee($cat2->name);
    $response->assertSee($cat2->description);
    $this->assertNotContains($cat3->name, $response->getContent());
  }

  /**
   * @test
   */
  public function homePageGetsCategoriesInTheCorrectOrder()
  {
    factory(Category::class)->create(['name' => 'cat 3', 'sort_order' => 3])->images()->sync([
      factory(Image::class)->create()->id => ['category_sort_order' => 1]
    ]);
    factory(Category::class)->create(['name' => 'cat 1', 'sort_order' => 1])->images()->sync([
      factory(Image::class)->create()->id => ['category_sort_order' => 1]
    ]);
    factory(Category::class)->create(['name' => 'cat 2', 'sort_order' => 2])->images()->sync([
      factory(Image::class)->create()->id => ['category_sort_order' => 1]
    ]);


    $response = $this->get('/');
    $categories = $response->original->getData()['categories']->toArray();
    $this->assertEquals('cat 1', $categories[0]['name']);
    $this->assertEquals('cat 2', $categories[1]['name']);
    $this->assertEquals('cat 3', $categories[2]['name']);
  }
}
