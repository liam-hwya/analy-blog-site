<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Category;
use App\MetaphoneText;
use App\SoundexText;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon as SupportCarbon;

class BlogController extends Controller
{
    public function index(){
      
    }

    public function create(){

      $categories = Category::all();

      return view('blogs.add',[
        'categories' => $categories
      ]);
    }

    public function store(Request $request){
      
      $blog = new Blog();
      $blog->title = $request->title;
      $blog->desc = $request->desc;
      $blog->content = $request->content;
      $blog->category_id = $request->category_id;
      $blog->author = auth()->user()->name;
      $blog->save();

      $blog_id_obj = Blog::latest()->first();

      $blog_id = $blog_id_obj->id;

      // Prepare for storing in metaphone

      $metaphone = new MetaphoneText();
      $metaphone->title = metaphone($request->title);
      $metaphone->desc = metaphone($request->desc);
      $metaphone->content = metaphone($request->content);
      $metaphone->blog_id = $blog_id;
      $metaphone->save();

      // Prepare for storing in soundex

      $soundex_title = str_replace(' ','',$request->title);
      $soundex_desc = str_replace(' ','',$request->desc);
      $soundex_content = str_replace(' ','',$request->content);

      $soundex = new SoundexText();
      $soundex->title = soundex($soundex_title);
      $soundex->desc = soundex($soundex_desc);
      $soundex->content = soundex($soundex_content);
      $soundex->blog_id = $blog_id;
      $soundex->save();

      return redirect()->route('home')->with('success','You has uploaded a post');
    }

    public function show($id){
      
      $blog = Blog::find($id);
      $category = Category::where('name',$blog->category->name)->value('id');

      $relatedBlogs = Blog::where('category_id',4)->latest()->paginate(4);

      return view('blogs.view',[
        'blog' => $blog,
        'relatedBlogs' => $relatedBlogs
      ]);
    }

    public function showByCategory($category){
      
      $cat_id = Category::where('name',$category)->value('id');

      $blogs = Blog::where('category_id',$cat_id)->latest()->get();

      return view('blogs.categories.index',[
        'blogs' => $blogs,
        'category' => $category
      ]);
    }
    
    public function searchBlog(Request $request){

      $start_time = microtime(true);
      
      // **************************Traditional way of searching

      // $keyword = $request->keyword;

      // $results = Blog::where('title','LIKE','%'.$keyword.'%')
      //   ->orWhere('desc','LIKE','%'.$keyword.'%')
      //   ->orWhere('content','LIKE','%'.$keyword.'%')
      //   ->get()
      //   ->toArray();

      // **********************************

      // **************************Way of using metaphone algorithm

      $keyword = metaphone($request->keyword);

      $resultsObjs = MetaphoneText::where('title','LIKE','%'.$keyword.'%')
        ->orWhere('desc','LIKE','%'.$keyword.'%')
        ->orWhere('content','LIKE','%'.$keyword.'%')
        ->get();

      if(count($resultsObjs)==0){

          $resultsArr = null;
  
        }else{

        $counter = 0;

        foreach($resultsObjs as $obj){
          $resultsArr[$counter]['id'] = $obj->getBlog->id;
          $resultsArr[$counter]['title'] = $obj->getBlog->title;
          $resultsArr[$counter]['desc'] = $obj->getBlog->desc;
          $resultsArr[$counter]['content'] = $obj->getBlog->content;
          $resultsArr[$counter]['author'] = $obj->getBlog->author;
          $resultsArr[$counter]['created_at'] = $obj->getBlog->created_at;
          $counter++;
        }

      }

      // *******************************************

      // ******************************** Way of using soundex algorithm

      // $request_keyword = str_replace(' ','',$request->keyword);
      // $keyword = soundex($request_keyword);

      // // dd($keyword);

      // $resultsObjs = SoundexText::where('title','LIKE','%'.$keyword.'%')
      //   ->orWhere('desc','LIKE','%'.$keyword.'%')
      //   ->orWhere('content','LIKE','%'.$keyword.'%')
      //   ->get();

      // $counter = 0; 

      // if(count($resultsObjs)==0){

      //   $resultsArr = null;

      // }else{

      //   foreach($resultsObjs as $obj){
      //     $resultsArr[$counter]['id'] = $obj->getBlog->id;
      //     $resultsArr[$counter]['title'] = $obj->getBlog->title;
      //     $resultsArr[$counter]['desc'] = $obj->getBlog->desc;
      //     $resultsArr[$counter]['content'] = $obj->getBlog->content;
      //     $resultsArr[$counter]['author'] = $obj->getBlog->author;
      //     $resultsArr[$counter]['created_at'] = $obj->getBlog->created_at;
      //     $counter++;
      //   }

      // }

      // ********************************************

      // ************************************* Way of using levenshtein algorithm

      // $keyword = $request->keyword;

      // $results = Blog::all();
      // $counter = 0;
      
      // $resultsArr = null;

      // foreach($results as $result){

      //   // dd(strlen($result->content));

      //   if(strlen($result->content)<=255){
          
      //     $title_lev = levenshtein($keyword,$result->title);
      //     $desc_lev = levenshtein($keyword,$result->desc);
      //     $content_lev = levenshtein($keyword,$result->content);
      

      //     if($title_lev >= 0 && $title_lev <=100 || $desc_lev >= 0 && $desc_lev <=100 ||  $content_lev >= 0 && $content_lev<=100 ){

      //       $resultsArr[$counter]['id'] = $result->id;
      //       $resultsArr[$counter]['title'] = $result->title;
      //       $resultsArr[$counter]['desc'] = $result->desc;
      //       $resultsArr[$counter]['content'] = $result->content;
      //       $resultsArr[$counter]['author'] = $result->author;
      //       $resultsArr[$counter]['created_at'] = $result->created_at;
      //       $counter++;

      //     }

      //   }

      // }
      
      // *******************************************

      // *****************************************Way of using similar text 

      // $keyword = $request->keyword;

      // $results = Blog::all();
      // $counter = 0;

      
      // $resultsArr = null;

      // foreach($results as $result){

      //   similar_text($keyword,$result->title,$title_perc);
      //   similar_text($keyword,$result->desc,$desc_perc);
      //   similar_text($keyword,$result->content,$content_perc);

      //   if($title_perc >= 20.0 || $desc_perc >= 20.0 || $content_perc >= 20.0 ){

      //       $resultsArr[$counter]['id'] = $result->id;
      //       $resultsArr[$counter]['title'] = $result->title;
      //       $resultsArr[$counter]['desc'] = $result->desc;
      //       $resultsArr[$counter]['content'] = $result->content;
      //       $resultsArr[$counter]['author'] = $result->author;
      //       $resultsArr[$counter]['created_at'] = $result->created_at;
      //       $counter++;

      //     }

      //   }


      // *******************************************

      $end_time = microtime(true);
      $duration = $end_time - $start_time;

      return view('blogs.search',[
        // 'results' => $results, //traditional way
        'results' => $resultsArr, //metaphone way,soundex way , levenshtein
        // 'keyword' => $keyword, //traditional way
        'keyword' => $request->keyword, //metaphone way,soundex way
        'message' => "These are ".count($resultsArr)." results from your search \" $keyword \"",
        'duration' => $duration
      ]);

    }
    
}
