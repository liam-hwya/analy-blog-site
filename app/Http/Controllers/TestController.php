<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function detail($id){
      return view('blogs.detail');
    }

    public function search(){
      return view('search.index');
    }

    public function category($category){
      return view('blogs.category');
    }
    
    public function add(){
      return view('blogs.add');
    }

    
}
