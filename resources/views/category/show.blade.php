@extends('layouts.app')

@section('content')
  <div class="category">
    <div class="category-heading">
      <h1>{{ $category->name }}</h1>
      <p>{{ $category->description }}</p>
    </div>

    <image-list :images="{{json_encode($category->images->toArray())}}" />
  </div>
@endsection