@extends('layouts.master')

@section('content')

  <div class="search-result-container">
    <h2>Search Result</h2>
    <br>
    @if (!empty($results))
  <div class="alert alert-search">{{ $message }} in {{ $duration }}</div>
    <div class="search-results">

      {{-- Start Traditional way --}}
      {{-- *************************************** --}}
      
        {{-- End Traditional Way --}}

        {{-- Start metaphone way --}}
        {{-- ************************************* --}}
        @foreach($results as $result)
        <div class="result">
          <div>
            <a href="{{ route('show-blog',$result['id']) }}" class="blog-title-link">
              <h3 class="blog-title">
                <?php
                  $result['title'] = str_replace($keyword,"<span class='highlight'>$keyword</span>",$result['title']);
                  echo $result['title'];
                ?>
              </h3>
            </a>
            <p class="blog-description">
              <?php $result['desc'] = str_replace($keyword,"<span class='highlight'>$keyword</span>",$result['desc']);
              echo $result['desc'] ?>
            </p>
            <span class="blog-author">{{ $result['author'] }}</span>
            <span class="blog-date">{{ $result['created_at'] }}</span>
          </div>  
          <img src="https://picsum.photos/id/{{ $result['id']+1 }}/200/100" alt="blog-image">
        </div>
        @endforeach
        {{-- End metaphone way --}}

      @else
      <div class="alert alert-danger">
        There is no result from your search
      </div>
      @endif
    </div>
  </div>

  @endsection

  