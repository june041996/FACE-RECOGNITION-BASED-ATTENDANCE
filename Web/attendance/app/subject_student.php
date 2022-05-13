<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\subject;
class subject_student extends Model
{
    //
    protected $guarded=[];
    protected $table = 'subject_student';
    protected $fillable=['id','mssv','id_subject'];
     public $timestamps = false;
     public function student()
    {
        return $this->hasMany(student::class,'mssv');
    }
}
