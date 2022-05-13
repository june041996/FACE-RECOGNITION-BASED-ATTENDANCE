<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class teacher extends Model
{
    //
    protected $guarded=[];
    protected $table = 'teacher';
    protected $fillable=['id','name','phone_number'];
    public $timestamps = false;
}
