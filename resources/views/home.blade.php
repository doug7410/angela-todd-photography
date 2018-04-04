@extends('layouts.app')

@section('content')
<carousel slides="{{$slides}}"></carousel>

<div class="portfolio" id="portfolio">
  <h1>Portfolio</h1>
  <div class="items">
    @foreach($categories as $category)
    <a href="/category/{{$category->id}}" class="item">
      @if($category->image)
        <img src="{{$category->image->path}}" alt="">
      @endif
      <h2>{{ $category->name }}</h2>
      <small>{{ $category->description }}</small>
    </a>
    @endforeach
  </div>
</div>
@endsection