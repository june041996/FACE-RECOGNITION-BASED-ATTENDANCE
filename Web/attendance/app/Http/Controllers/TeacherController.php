<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\teacher;
use App\Http\Requests\TeacherCreateRequest;
use App\Http\Requests\TeacherEditRequest;
class TeacherController extends Controller
{
    //
    public function index()
    {
        $Teacher=teacher::all();
        //dd($Comments->find(1)->comics()->first()->comics_name);
        return view('admin.teacher.index',compact('Teacher'));
    }

     public function create()
    {
        $Teacher=teacher::all();
        return view('admin.teacher.create',compact('Teacher'));
    }

    public function store(TeacherCreateRequest $request)
    {
        
        teacher::create([
            'name'=>$request->name,
            'phone_number'  =>  $request->phone_number
        ]);
        $message="Thêm giảng viên thành công";
        return redirect()->route('teacher.index')->with('alert', $message);
    }

    public function edit($id)
    {
    
        $Teacher=teacher::find($id);
        return view('admin.teacher.edit',compact('Teacher'));
    }

    public function update($id,TeacherEditRequest $request)
    {
        
        teacher::find($id)->update([
            'name'=>$request->name,
            'phone_number'  =>  $request->phone_number
        ]);
        $message="Cập nhập giảng viên thành công";
        return redirect()->route('teacher.index')->with('alert', $message);
    }

    public function delete($id)
    {
        try{
            teacher::find($id)->delete();
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
