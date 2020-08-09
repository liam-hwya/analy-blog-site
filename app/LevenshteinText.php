<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LevenshteinText extends Model
{
    public function getBlog(){
        return $this->belongsTo('App\Blog','blog_id');
    }
}
