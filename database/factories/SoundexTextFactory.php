<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SoundexText;
use Faker\Generator as Faker;

$factory->define(SoundexText::class, function (Faker $faker) {
    return [
        "title" => function($blog){
            $title = App\Blog::find($blog['blog_id'])->title;
            return soundex($title);
        },
        "desc" => function($blog){
            $desc = App\Blog::find($blog['blog_id'])->desc;
            return soundex($desc);
        },
        "content" => function($blog){
            $content = App\Blog::find($blog['blog_id'])->content;
            return soundex($content);
        },
        "blog_id" => $faker->unique(true)->numberBetween(1,100),
    ];
});
