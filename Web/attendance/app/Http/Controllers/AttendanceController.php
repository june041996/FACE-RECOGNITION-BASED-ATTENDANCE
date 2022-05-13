<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\attendances;
use App\subject;
use App\student;
use Illuminate\Support\Facades\DB;
class AttendanceController extends Controller
{
    //
     public function index()
    {
        $Subject=subject::all();
        $Students=student::all();
        $Attendance=student::all();
        $Name_subject='WEB';
        //dd($Comments->find(1)->comics()->first()->comics_name);
        return view('admin.attendance.index',compact('Attendance','Students','Subject','Name_subject'));
    }

    public function search(Request $request)
    {
        $date=$request->date;
        $subject=$request->subject;
        //dd($date);
         $Attendance=DB::table('student')->rightjoin('attendances', 'student.mssv', '=', 'attendances.mssv')->rightjoin('subject', 'attendances.id_subject', '=', 'subject.id')->where('attendances.date','=',$date)->where('subject.name','=',$subject)->select('student.mssv')->get();;
         //dd($Attendance);
         foreach ($Attendance as $user) {
                $data[] = $user->mssv;
            }

         $Students=DB::table('student')->rightjoin('subject_student', 'student.mssv', '=', 'subject_student.mssv')->rightjoin('subject', 'subject_student.id_subject', '=', 'subject.id')->where('subject.name','=',$subject)->select()->get();;
         //dd($data);
        $Subject=subject::all();
        $Name_subject=$subject;
        //$Attendance=attendances::all();
        //dd($Comments->find(1)->comics()->first()->comics_name);
        return view('admin.attendance.index',compact('Attendance','Subject','Students','Name_subject'));
    }
}
