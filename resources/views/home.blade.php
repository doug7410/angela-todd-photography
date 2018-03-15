@extends('layouts.app')

@section('content')
<carousel></carousel>

<div class="portfolio">
  <h1>Portfolio</h1>
  <div class="items">
    @foreach($categories as $category)
    <a href="/category/{{$category->id}}" class="item">
      <img src="{{$category->image->path}}" alt="">
      <h2>{{ $category->name }}</h2>
      <small>{{ $category->description }}</small>
    </a>
    @endforeach
  </div>
</div>
@endsection