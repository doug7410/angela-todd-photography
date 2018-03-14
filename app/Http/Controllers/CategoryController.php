<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($id) {
        $category = Category::with(['images' => function($query) {
            return $query->orderBy('pivot_category_sort_order')->get();
        }])->where('id', $id)->first();

        return view('category.show', ['category' => $category]);
    }
}
