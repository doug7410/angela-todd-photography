<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function image() {
        return $this->belongsTo(Image::class);
    }
}
