<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class attendances extends Model
{
    protected $guarded=[];
    protected $table = 'attendances';
    protected $fillable=['id','subject','mssv','date','time','id_subject'];
}
