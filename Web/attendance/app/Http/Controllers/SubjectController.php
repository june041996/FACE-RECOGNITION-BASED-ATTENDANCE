<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\subject;
use App\teacher;
class SubjectController extends Controller
{
    //
    public function index()
    {
        $Subject=subject::all();
        //dd($Comments->find(1)->comics()->first()->comics_name);
        return view('admin.subject.index',compact('Subject'));
    }

      public function create()
    {
        $Subject=subject::all();
        $Teacher=teacher::all();
        return view('admin.subject.create',compact('Subject','Teacher'));
    }

    public function store(Request $request)
    {
        
        $id_teacher=teacher::where('name','=',$request->name)->first();
       // dd($id_teacher->id);
        subject::create([
            'name'=>$request->name1,
            'time_start'  =>  $request->time_start,
            'time_end'  =>  $request->time_end,
            'day_start'  =>  $request->day_start,
            'day_end'  =>  $request->day_end,
            'DoW'  =>  $request->DoW,
            'id_teacher'  =>  $id_teacher->id
        ]);
        $message="Thêm môn học thành công";
        return redirect()->route('subject.index')->with('alert', $message);
    }

    public function edit($id)
    {
        $Teacher=teacher::all();
        $Subject=subject::find($id);
        return view('admin.subject.edit',compact('Subject','Teacher'));
    }

    public function update($id,Request $request)
    {
        $id_teacher=teacher::where('name','=',$request->name)->first();
        subject::find($id)->update([
            'name'=>$request->name1,
            'time_start'  =>  $request->time_start,
            'time_end'  =>  $request->time_end,
            'day_start'  =>  $request->day_start,
            'day_end'  =>  $request->day_end,
            'DoW'  =>  $request->DoW,
            'id_teacher'  =>  $id_teacher->id
        ]);
        $message="Cập nhập môn học thành công";
        return redirect()->route('subject.index')->with('alert', $message);
    }

    public function delete($id)
    {
        try{
            subject::find($id)->delete();
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
