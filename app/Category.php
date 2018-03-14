<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function image() {
        return $this->belongsTo(Image::class);
    }

    public function images() {
        return $this->belongsToMany(Image::class)->withPivot('category_sort_order');
    }
}
