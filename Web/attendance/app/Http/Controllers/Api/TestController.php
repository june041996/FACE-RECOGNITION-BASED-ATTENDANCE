<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comic;
use App\Chapter;
use App\Image;
use App\Category;
use App\Comics_Category;
use App\Follow;
use App\User;
use App\Comment;
use App\student;
use Illuminate\Support\Facades\Storage;
use DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Carbon\Carbon;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function getTotalDay($id_subject)
    {   
        $day_start =DB::table('subject')
                
                ->select('day_start')
                ->where('subject.id',$id_subject)
                
                ->first()->day_start;
          $day_end =DB::table('subject')
                
               
                ->where('subject.id',$id_subject)
                
                ->first()->day_end;
                      
                //$total=$day_end-$day_start;
                $d2 = Carbon::parse($day_end);
                $d1 = Carbon::parse($day_start);
                $difference = $d2->diffInDays($d1, false)   ; 
                $total=$difference/7;
               
        return ceil($total);
                //return $s;
      }
    public function getCount($mssv, $id_subject)
    {   
        $day_start =DB::table('subject')
                
                ->select('day_start')
                ->where('subject.id',$id_subject)
                
                ->first()->day_start;
          $day_end =DB::table('subject')
                
               
                ->where('subject.id',$id_subject)
                
                ->first()->day_end;
                      
                //$total=$day_end-$day_start;
                $d2 = Carbon::parse($day_end);
                $d1 = Carbon::parse($day_start);
                $difference = $d1->diffInDays($d2, false)   ; 
                $total=ceil($difference/7);

        $count =DB::table('attendances')
                
                ->select('attendances.*')
                ->where('attendances.mssv',$mssv)
                ->where('attendances.id_subject',$id_subject)
                ->count();
                $result=$count."/".(string)$total;
        return $result;
      }
    public function getAllSubject($mssv)
    {
        $result =DB::table('subject')
                ->leftJoin('subject_student', 'subject.id', '=', 'subject_student.id_subject')
                ->leftJoin('teacher', 'subject.id_teacher', '=', 'teacher.id')
                ->select('subject.*','subject_student.mssv','teacher.name AS name_teacher')
                ->where('subject_student.mssv',$mssv)
                ->get();
        return $result;
      }

     public function getSubject($mssv)
    {
        $dayOfWeek = Carbon::now()->format('l');
        $result =DB::table('subject')
                ->leftJoin('subject_student', 'subject.id', '=', 'subject_student.id_subject')
                ->leftJoin('teacher', 'subject.id_teacher', '=', 'teacher.id')
                ->select('subject.*','subject_student.mssv','teacher.name AS name_teacher')
                ->where('subject_student.mssv',$mssv)
                ->where('subject.DoW',$dayOfWeek)
                ->get();
        return $result ;
      } 

      public function test (Request $request)
    {   
         $img_new_name = "main.jpg";
        Storage::put('public/py/'.$img_new_name, base64_decode($request->imageCode));
        echo "1";
     }
    public function attendance (Request $request)
    {   
        $date = date('Y-m-d');
        $time = date('H:i');
        $id_subject= $request->id_subject;
        $img_new_name = "main.jpg";
        //$param1  = $request->imageCode;
       /* Storage::put('public/py/'.$img_new_name, base64_decode($request->imageCode));
        $python = shell_exec("py C:/xampp/htdocs/comic/public/storage/py/test.py");*/
        //echo $python;   
        //if($python==$request->mssv){p
        $subject=DB::table('subject')
                ->where('subject.id',$id_subject)->first();
        $time_start =$subject->time_start;
        $time_end   =$subject->time_end;
        $day_start  =$subject->day_start;
        $day_end    =$subject->day_end;
        if($time>=$time_start && $time_end>=$time){
            if($date>=$day_start && $date <=$day_end){
                Storage::put('public/py/'.$img_new_name, base64_decode($request->imageCode));
                $python = shell_exec("C:/xampp/htdocs/comic_v0/comic/public/storage/py/test.py");
                //$python='1';
                if($python!=0){
                    //nhan dang dc guong mat
                    $check=DB::table('attendances')
                ->where('attendances.id_subject',$id_subject)->where('attendances.mssv',$request->mssv)->first();
                    if($check){
                        //đã điểm danh
                        echo '6';
                    }else{
                        $result=DB::table('attendances')->insert([
                        'mssv'=>$request->mssv,
                        'date'=>$date,
                        'time'=>$time,
                        'id_subject'=>$request->id_subject
                    ]);
                        $result="1";
                        if($result){
                            //them diem danh thanh cong
                            echo "1";;
                        }else{
                            //them diem danh that bai
                            echo "3";;
                        }
                    }
                    
                    
                }else{
                    if($python==0){
                        //Khong nhan dang duwoc guong mat
                        echo "0";
                    }else{
                        //nhan dang dc guong mat nhung chua chinh xac
                        echo "2";
                    }
                }
            }else{
                //date
                echo "4";
            }
        }else{
            //time
             echo "5";
        }
               
        
    }

    public function login(Request $request)
    {
        $result=DB::table('Student')->where('mssv',$request->mssv)->first();
        if($result!=null){
            /*$password_bcrypt= $result->password;
            if (password_verify($request->password, $password_bcrypt)) {
                return $result->id;
            } else {
                return 2;
            }*/
            $password= $result->password;
            if (($request->password == $password)) {
                return $result->mssv;
            } else {
                return 2;
            }
        }else{
            return 0;
        }
    }

    public function checkAttendance(Request $request){
        $result=DB::table('attendances')->where('mssv',$request->mssv)->where('id_subject',$request->id_subject)->first();
        if ($result != null) {
            return 1;
        }else{
            return 0;
        }
    }

    public function changePass(Request $request)
    {

        if($request->pass1 == $request->pass2){
            $check=student::where('mssv',$request->user)->first();
            if($check!=null){
                $password_bcrypt= $check->password;
                if ($request->oldPass== $password_bcrypt) {
                    student::where('mssv',$request->user)->update([
                    'password'=>$request->pass1
                    ]);
                    return 1;
                } else {
                    return 0;
                }
                
            }else{
                return 0;
            }
        }else{
            return 2;
        }
    }


/////////////////////////////////////////////////////////////////////////////////////////////////

    public function index()
    {
        return Comic::orderBy('view','DESC')->get();
    }

    public function chapter($id)
    {
        return Comic::find($id)->chapter()->get();
    }

    public function image($id)
    {
        return Chapter::find($id)->image()->get();
    }
    public function category()
    {
        $result =DB::table('comics')
                ->leftJoin('comics__categories', 'comics.id', '=', 'comics__categories.comics_id')
                ->leftJoin('categories', 'comics__categories.category_id', '=', 'categories.id')
                ->select('comics.*','categories.category_name')
                ->get();
        return $result;
    }
    public function comics_category($id )
    {
        return Comic::find($id)->category()->get();
    }
    public function follow($name)
    {
        $result =DB::table('comics')
        ->leftJoin('follows', 'comics.id', '=', 'follows.comics_id')
        ->leftJoin('users', 'follows.user_id', '=', 'users.id')
        ->select('comics.*','follows.follow_date')
        ->where('users.name',$name)
        ->get();
        return $result;
    }

    
    public function register(Request $request)
    {
        $check=0;
        $check=User::where('name',$request->username)->count();
        if($check==0){
            $result=User::create([
            'name'=>$request->username,
            'password'=>bcrypt($request->password),
            'email'=>$request->email
            ]);
            return 1;
        }else{
            return 0;
        }

        
    }
    
    public function createFollow(Request $request)
    {
        $check=Follow::where('user_id',$request->user_id)->where('comics_id',$request->comics_id)->count();
        if($check==0){
            $result=Follow::create([
            'user_id'=>$request->user_id,
            'comics_id'=>$request->comics_id,
            'follow_date'=>$request->follow_date
        ]);
            return 1;
        }else{
            return 0;
        }
        
    }
    public function getFollow(Request $request)
    {
        $result=Follow::where('comics_id',$request->comics_id)->where('user_id',$request->user_id)->count();
        if($result){
            return 1;
        }else{
            return 0;
        }
    }
    public function deleteFollow(Request $request)
    {
        $Comics=User::where('name',$request->name)->first();
        $id=$Comics->id;

        $result=Follow::where('comics_id',$request->comics_id)->where('user_id',$id)->delete();
        return $result;
        if($result){
            return 1;
        }else{
            return 0;
        }
    }

    public function getComment($id)
    {
        $articles =DB::table('comics')
        ->leftJoin('comments', 'comics.id', '=', 'comments.comics_id')
        ->leftJoin('users', 'comments.user_id', '=', 'users.id')
        ->select('comics.*','comments.content','comments.comment_date','comments.id as comment_id','users.id as user_id','users.name','users.image_path')
        ->where('comics.id',$id)
        ->get();
        return $articles;
    }

    public function deleteComment($id)
    {
        $result=Comment::where('id',$id)->delete();
        if($result){
            return 1;
        }else{
            return 0;
        }
    }

    public function postComment(Request $request)
    {
        $result=Comment::create([
            'user_id'=>$request->user_id,
            'comics_id'=>$request->comics_id,
            'comment_date'=>$request->comment_date,
            'content'=>$request->content
        ]);
        if($result){
            return 1;
        }else{
            return 0;
        }
    }

    public function getInforUser($name)
    {
        return DB::table('users')->where('name',$name)->select('id','name','email','gender','birthday','phone_number','address','image_path','image_name')->get();
    }

    public function changeInforUser(Request $request)
    {
        
       // $path =__DIR__ . "/../comic/public/storage/users/".$request->img_name;
        //return $path;
        if(($request->img_code)!=0){
            $img_new_name=date('YmdHis') . '_' . $request->img_name;
            //return $img_path;
            //luu anh
            Storage::put('public/users/'.$img_new_name, base64_decode($request->img_code));
            //xoa anh cu
            Storage::delete('public/users/'.$request->img_old_name);
            $path='/storage/users/'.$img_new_name;
            User::where('name',$request->name)->update([
                
                'email'=>$request->email,
                'gender'=>$request->gender,
                'birthday'=>$request->birthday,
                'phone_number'=>$request->phone_number,
                'address'=>$request->address,
                'image_path'=>$path,
                'image_name'=>$img_new_name

            ]);
            return 1;
        }else{
            User::where('name',$request->name)->update([
                
                'email'=>$request->email,
                'gender'=>$request->gender,
                'birthday'=>$request->birthday,
                'phone_number'=>$request->phone_number,
                'address'=>$request->address
            ]);
            return 1;
        }
    }

    public function countView(Request $request)
    {
        $id=$request->comics_id;
        
        
        $view=Comic::where('id',$id)->first()->view;
        $views=$view+1;
        Comic::where('id',$id)->update([
                'view'=>$views
            ]);

       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
