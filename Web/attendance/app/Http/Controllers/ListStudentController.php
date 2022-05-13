<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\subject;
use App\subject_student;
use App\student;
use Illuminate\Support\Facades\DB;
class ListStudentController extends Controller
{
    //
    public function index($id)
    {
        $Subject=subject::find($id);
        /*$List=$Subject->subject_student()->get()->first();*/

        $List=DB::table('subject')->rightjoin('subject_student', 'subject.id', '=', 'subject_student.id_subject')->rightjoin('student', 'subject_student.mssv', '=', 'student.mssv')->where('subject.id','=',$id)->select('name','student.mssv','student.username','subject_student.id as id_subject_student','subject.id')->get();;
       // dd($List);
        //dd($Comments->find(1)->comics()->first()->comics_name);
        return view('admin.list_student.index',compact('List','Subject'));
    }

      public function create($id)
    {
        $List=DB::table('subject')->rightjoin('subject_student', 'subject.id', '=', 'subject_student.id_subject')->rightjoin('student', 'subject_student.mssv', '=', 'student.mssv')->where('subject.id','=',$id)->select('student.id as id')->get()->toArray();
        $data[]='';
        foreach ($List as $user) {
                $data[] = $user->id;
            }

        $Student=DB::table('student')->whereNotIn('id',$data)->get();
       //dd($re);
        $Subject=subject::find($id);
        //$Student=student::all();
        return view('admin.list_student.create',compact('Subject','Student'));
    }

    public function store($id, $id_subject)
    {
        //dd($id);
       // $id_teacher=teacher::where('name','=',$request->name)->first();
       // dd($id_teacher->id);
        subject_student::create([
            'id_subject'=>$id_subject,
            'mssv'  =>  $id
        ]);
        $message="Thêm SV thành công";
        return redirect()->route('list_student.create',['id'=>$id_subject])->with('alert', $message);
    }

    

    public function delete($id1)
    {
        try{
            subject_student::find($id1)->delete();
             return response()->json([
                'code'=>200,
                'message'=>'success'
            ],200);
        }catch(\Exception $exception){
            // Log::error('message'.$exception->getMessage().'Line:'.$exception->getFile());

            return response()->json([
                'code'=>500,
                'message'=>'fail'
            ],500);
        }
    }
}
