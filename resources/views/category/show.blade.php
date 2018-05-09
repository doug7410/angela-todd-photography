@extends('layouts.app')

@section('content')
  <div class="category">
    <div class="category-heading">
      <h1>{{ $category->name }}</h1>
      <p>{{ $category->description }}</p>
    </div>
    <div class="items">
      @foreach($category->images as $image)
        <a href="#" class="item" @click.prevent="currentImageId = {{$image->id}}">
          <img src="{{$image->path}}" alt="{{$image->caption}}">
          <small>{{$image->caption}}</small>
        </a>
      @endforeach
    </div>
    <photo-modal :images="{{json_encode($category->images->toArray())}}" v-model="currentImageId" />
  </div>
@endsection