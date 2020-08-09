<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\MetaphoneText;
use Faker\Generator as Faker;

$factory->define(MetaphoneText::class, function (Faker $faker) {
    // return [
    //     "title" => ucwords($faker->catchPhrase .' '.$faker->bs),
    //     "desc" => $faker->realText($maxNbChars = 50),
    //     "content" => $faker->realText($maxNbChars = 1000),
    //     "blog_id" => rand(1,5),
    // ];


    return [
        "title" => function($blog){
            $title = App\Blog::find($blog['blog_id'])->title;
            return metaphone($title);
        },
        "desc" => function($blog){
            $desc = App\Blog::find($blog['blog_id'])->desc;
            return metaphone($desc);
        },
        "content" => function($blog){
            $content = App\Blog::find($blog['blog_id'])->content;
            return metaphone($content);
        },
        "blog_id" => $faker->unique(true)->numberBetween(1,100),
    ];
});
