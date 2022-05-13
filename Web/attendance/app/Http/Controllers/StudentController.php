<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\student;
use App\Http\Requests\StudentEditRequest;
class StudentController extends Controller
{
    //
    public function index()
    {
        $Student=student::all();
        //dd($Comments->find(1)->comics()->first()->comics_name);
        return view('admin.student.index',compact('Student'));
    }



    public function edit($id)
    {
    
        $Student=student::find($id);
        return view('admin.student.edit',compact('Student'));
    }

    public function update($id,StudentEditRequest $request)
    {
        
        student::find($id)->update([
            'name'=>$request->name,
            'mssv'  =>  $request->mssv,
            'password'  =>  $request->password,
        ]);
        $message="Cập nhập sinh viên  thành công";
        return redirect()->route('student.index')->with('alert', $message);
    }

    public function delete($id)
    {
        try{
            student::find($id)->delete();
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
