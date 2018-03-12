<?php

namespace Tests\Feature;

use App\Category;
use App\Image;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomePagePortfolioTest  extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function homePageShowsAllCategories()
    {
        $cat1Image = factory(Image::class)->create();
        $cat2Image = factory(Image::class)->create();
        $cat1 = factory(Category::class)->create(['image_id' => $cat1Image->id]);
        $cat2 = factory(Category::class)->create(['image_id' => $cat2Image->id]);

        $response = $this->get('/');

        $response->assertSee('/category/' . $cat1->id);
        $response->assertSee($cat1->image->path);
        $response->assertSee($cat1->name);
        $response->assertSee($cat2->description);$response->assertSee('/category/' . $cat2->id);
        $response->assertSee($cat2->image->path);
        $response->assertSee($cat2->name);
        $response->assertSee($cat2->description);
    }

    /**
     * @test
     */
    public function homePageGetsCategoriesInTheCorrectOrder()
    {
        factory(Category::class)->create(['name' => 'cat 3', 'sort_order' => 3]);
        factory(Category::class)->create(['name' => 'cat 1', 'sort_order' => 1]);
        factory(Category::class)->create(['name' => 'cat 2', 'sort_order' => 2]);

        $response = $this->get('/');
        $categories = $response->original->getData()['categories']->toArray();
        $this->assertEquals('cat 1', $categories[0]['name']);
        $this->assertEquals('cat 2', $categories[1]['name']);
        $this->assertEquals('cat 3', $categories[2]['name']);
    }
}
