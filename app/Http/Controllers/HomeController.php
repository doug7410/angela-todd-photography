<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('home', [
            'categories' => Category::with('image')->orderBy('sort_order')->get()
        ]);
    }
}
