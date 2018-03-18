<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Jobs\ImportImage;
use Cloudinary\Uploader;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

Route::get('/', 'HomeController@index');

Route::get('/category/{id}', 'CategoryController@show');

Route::get('/test', function () {
  $image = [
    'file' => 'Title.jpg',
    'caption' => 'Some cool places',
    'categories' => [
      ['name' => 'landscape', 'sort_order' => 1],
      ['name' => 'portrait', 'sort_order' => 1]
    ],
    'slider' => 1
  ];

  $i = ImportImage::dispatch($image);

  return \response()->json($i);
});