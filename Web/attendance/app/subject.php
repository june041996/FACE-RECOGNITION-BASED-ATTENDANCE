<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\subject;
use App\subject_student;
class subject extends Model
{
    //
    protected $guarded=[];
    protected $table = 'subject';
    protected $fillable=['id','name','time_start','time_end','day_start','day_end','DoW','id_teacher'];
      public $timestamps = false;

      public function teacher()
    {
        return $this->belongsTo(teacher::class,'id_teacher');
    }

     public function subject_student()
    {
        return $this->hasMany(subject_student::class,'id_subject');
    }
}
