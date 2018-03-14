<?php

use App\Image;
use Illuminate\Database\Seeder;

class CategoryImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = \App\Category::all();

        $categories->each(function ($category) {
            for($i = 1; $i <= 10; $i++) {
                $category->images()->save(factory(Image::class)->create(), ['category_sort_order' => $i]);
            }
        });

    }
}
