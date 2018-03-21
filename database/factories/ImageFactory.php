<?php

use Faker\Generator as Faker;

$factory->define(App\Image::class, function (Faker $faker) {
  return [
    'image_name' => $faker->word,
    'path' => $faker->imageUrl(),
    'caption' => $faker->sentence(),
    'meta_data' => json_encode(['foo' => 'bar', 'another' => 'foo bar'])
  ];
});
