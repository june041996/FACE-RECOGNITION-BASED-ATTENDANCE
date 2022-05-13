<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class student extends Model
{
    //
    protected $guarded=[];
    protected $table = 'student';
    protected $fillable=['id','username','mssv','password'];
      public $timestamps = false;
}
