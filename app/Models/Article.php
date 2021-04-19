<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Posts class instance will refer to posts table in database
class Article extends Model
{
  //restricts columns from modifying
  protected $guarded = [];

  
  
  // returns the instance of the user who is author of that post
  public function author()
  {
    return $this->belongsTo('App\Models\User', 'author_id');
  }
}