<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $blog = factory(App\Blog::class,3)
        // ->create()
        // ->each(function($blog){
        //     factory(App\MetaphoneText::class)->create();
        // });
        $blog = factory(App\Blog::class,100)->create();
        factory(App\MetaphoneText::class,100)->create();
        factory(App\SoundexText::class,100)->create();


        factory(App\User::class,1)->create();
        factory(App\Category::class,5)->create();
    }
}
