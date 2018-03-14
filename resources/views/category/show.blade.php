@extends('layouts.app')

@section('content')
  <div class="category">
    <div class="category-heading">
      <h1>{{ $category->name }}</h1>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
    </div>
    <div class="items">
      @foreach($category->images as $image)
        <a href="#" class="item" @click.prevent="showModal('{{$image->path}}')">
          <img src="{{$image->path}}" alt="{{$image->caption}}">
          <small>{{$image->caption}}</small>
        </a>
      @endforeach
    </div>
    <photo-modal :images="{{json_encode($category->images->toArray())}}" :initial-image-id="3"></photo-modal>
  </div>
@endsection