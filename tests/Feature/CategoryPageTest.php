<?php

namespace Tests\Feature;

use App\Category;
use App\Image;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryPageTest  extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function visitingTheCategoryPageShowsTheCorrectCategoryName()
    {
        $category = factory(Category::class)->create(['name' => 'Foo Category Name']);
        $response = $this->get("/category/$category->id");
        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    /**
     * @test
     */
    public function theCategoryPageShowsTheCorrectImages()
    {
        $category = factory(Category::class)->create();
        $img1 = $category->images()->save(factory(Image::class)->create(), ['category_sort_order' => 1]);
        $img2 = $category->images()->save(factory(Image::class)->create(), ['category_sort_order' => 2]);

        $response = $this->get("/category/$category->id");
        $response->assertStatus(200);
        $response->assertSee($img1->path);
        $response->assertSee($img1->caption);
        $response->assertSee($img2->path);
        $response->assertSee($img2->caption);
    }

    /**
     * @test
     */
    public function theCategoryPageShowsImagesInTheCorrectOrder()
    {
        $category = factory(Category::class)->create();
        $category->images()->save(factory(Image::class)->create(['path' =>'path/1']), ['category_sort_order' => 3]);
        $category->images()->save(factory(Image::class)->create(['path' =>'path/2']), ['category_sort_order' => 1]);
        $category->images()->save(factory(Image::class)->create(['path' =>'path/3']), ['category_sort_order' => 2]);

        $response = $this->get("/category/$category->id");
        $images = $response->original->getData()['category']->toArray()['images'];

        $this->assertEquals('path/2', $images[0]['path']);
        $this->assertEquals('path/3', $images[1]['path']);
        $this->assertEquals('path/1', $images[2]['path']);
    }
}
