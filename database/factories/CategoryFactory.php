<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'image_id' => factory(\App\Image::class)->create()->id,
        'sort_order' => $faker->numberBetween(1,99)
    ];
});
