<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    public function image() {
      return $this->belongsTo(Image::class);
    }
}
