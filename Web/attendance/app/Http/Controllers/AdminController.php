<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Comic;
use App\Chapter;
use App\ComicsImage;
use App\Comment;
use App\Follow;
use App\User;
use App\subject;
use App\attendances;
use App\student;
use App\teacher;








class AdminController extends Controller
{
    public function loginAdmin()
    {
        if (Auth()->check()) {
            return redirect()->to('home');
        }
        return view('login');
    }

    public function logout()
    {
        Auth()->logout();
        return redirect()->route('login');
    }

    public function postLoginAdmin(Request $request)
    {
       //dd(bcrypt('123'));
        //$remember = $request->has('remember_me') ? true : false;
        if (Auth()->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'name'=>'admin'
        ])) {
            
            return redirect()->to('home');
        } else{
            return redirect()->route('login');

        }
        
    }
    public function home()
    {
        /*$total_comics=Comic::all()->count();
        $total_chapter=Chapter::all()->count();
        $total_image=ComicsImage::all()->count();
        $total_comment=Comment::all()->count();
        $total_follow=Follow::all()->count();
        $total_user=User::all()->count();
        $total_category=Category::all()->count();*/

        $total_comics=1;
        $total_chapter=1;
        $total_image=1;
        $total_comment=1;
        $total_follow='1';
        $total_user=1;
        $total_category=1;

        $total_teacher=teacher::all()->count();
        $total_student=student::all()->count();
        $total_subject=subject::all()->count();
        $total_user=User::all()->count();



        return view('home',compact('total_teacher','total_student','total_subject','total_user'));
    }
}