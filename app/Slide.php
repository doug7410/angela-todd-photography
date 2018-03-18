<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
  protected $guarded = [];
  
    public function image() {
      return $this->belongsTo(Image::class);
    }
}
