<?php

namespace App\Http\Controllers;

use App\Category;
use App\Slide;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index()
  {
    $categories = Category::with('image')->orderBy('sort_order')->get();
    $filteredCategories = $categories->filter(function($category){
      return $category->images->count() > 0;
    });
    return view('home', [
      'categories' => $filteredCategories,
      'slides' => Slide::with('image')->orderBy('sort_order')->get()->map(function($slide) {
        return ['id' => $slide['image']['id'], 'path' => $slide['image']['path']];
      })
    ]);
  }
}
