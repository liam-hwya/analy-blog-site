<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Blog;
use Faker\Generator as Faker;

$factory->define(Blog::class, function (Faker $faker) {
    return [
        "title" => ucwords($faker->catchPhrase .' '.$faker->bs),
        "desc" => $faker->realText($maxNbChars = 50),
        "content" => $faker->realText($maxNbChars = 500),
        "category_id" => rand(1,5),
        "author" => "admin"
    ];
});
