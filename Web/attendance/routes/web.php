<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/home', [
    'as' => 'home',
    'uses' => 'AdminController@home'
])->middleware('auth');
Route::get('/', [
    'as' => 'login',
    'uses' => 'AdminController@loginAdmin'
]);
Route::post('/login', [
    'as' => 'post-login',
    'uses' => 'AdminController@postLoginAdmin'
]);

Route::get('/logout', [
    'as' => 'logout',
    'uses' => 'AdminController@logout'
]);

Route::group(['prefix' => 'comics', 'middleware' => ['auth']],function(){

  
    //comics
    Route::prefix('comics')->group(function(){
        //index
        Route::get('/',[
            'as' => 'comics.index',
            'uses'=>'ComicsController@index'
        ]);
       
        //
        Route::get('/create', [
            'as'=>'comics.create',
            'uses'=>'ComicsController@create'
        ]);
        Route::post('/store', [
            'as'=>'comics.store',
            'uses'=>'ComicsController@store'

        ]);
        Route::get('/edit/{id}', [
            'as'=>'comics.edit',
            'uses'=>'ComicsController@edit'
        ]);
        Route::post('/update/{id}', [
            'as'=>'comics.update',
            'uses'=>'ComicsController@update'
        ]);
        Route::get('/delete/{id}', [
            'as'=>'comics.delete',
            'uses'=>'ComicsController@delete'
        ]);
//Chapter
            Route::get('/{id}/chapter/', [
                'as'=>'chapter.index',
                'uses'=>'ChapterController@index'
            ]);

            Route::get('/{id}/chapter/create', [
                'as'=>'chapter.create',
                'uses'=>'ChapterController@create'
            ]);

            Route::post('/{id}/chapter/store', [
                'as'=>'chapter.store',
                'uses'=>'ChapterController@store'
            ]);

            Route::get('/{id}/chapter/edit/{chapter_id}', [
                'as'=>'chapter.edit',
                'uses'=>'ChapterController@edit'
            ]);

            Route::post('/{id}/chapter/update/{chapter_id}', [
                'as'=>'chapter.update',
                'uses'=>'ChapterController@update'
            ]);

            Route::get('/{id}/chapter/delete/{chapter_id}', [
                'as'=>'chapter.delete',
                'uses'=>'ChapterController@delete'
            ]);
//Image
                Route::get('/{id}/chapter/{chapter_id}/image', [
                    'as'    =>  'image.index',
                    'uses'  =>  'ImageComicsController@index'
                ]);
                Route::get('/{id}/chapter/{chapter_id}/image/create', [
                    'as'    =>  'image.create',
                    'uses'  =>  'ImageComicsController@create'
                ]);
                Route::post('/{id}/chapter/{chapter_id}/image/store', [
                    'as'    =>  'image.store',
                    'uses'  =>  'ImageComicsController@store'
                ]);
                Route::get('/{id}/chapter/{chapter_id}/image/edit/{image_id}', [
                    'as'    =>  'image.edit',
                    'uses'  =>  'ImageComicsController@edit'
                ]);
                Route::post('/{id}/chapter/{chapter_id}/image/update/{image_id}', [
                    'as'    =>  'image.update',
                    'uses'  =>  'ImageComicsController@update'
                ]);
                Route::get('/{id}/chapter/{chapter_id}/image/delete/{image_id}', [
                    'as'    =>  'image.delete',
                    'uses'  =>  'ImageComicsController@delete'
                ]);
            
        
    });
    //category
    Route::prefix('category')->group(function(){
        //index
        Route::get('/',[
            'as' => 'category.index',
            'uses'=>'CategoryController@index'
        ]);
        Route::get('/create', [
            'as'=>'category.create',
            'uses'=>'CategoryController@create'
        ]);
        Route::post('/store',[
            'as' => 'category.store',
            'uses' => 'CategoryController@store'
        ]);
        Route::get('/edit{id}', [
            'as'=> 'category.edit',
            'uses'=> 'CategoryController@edit'
        ]);
        Route::post('/update/{id}',[
            'as'=>'category.update',
            'uses'=>'CategoryController@update'
        ]);
        Route::get('/delete/{id}', [
            'as'=>'category.delete',
            'uses'=>'CategoryController@delete'
        ]);

        

    });
//comment
    Route::prefix('comment')->group(function(){
        //index
        Route::get('/',[
            'as' => 'comment.index',
            'uses'=>'CommentController@index'
        ]);
        Route::get('/create', [
            'as'=>'comment.create',
            'uses'=>'CommentController@create'
        ]);
        Route::post('/store',[
            'as' => 'comment.store',
            'uses' => 'CommentController@store'
        ]);
        Route::get('/edit{id}', [
            'as'=> 'comment.edit',
            'uses'=> 'CommentController@edit'
        ]);
        Route::post('/update/{id}',[
            'as'=>'comment.update',
            'uses'=>'CommentController@update'
        ]);
        Route::get('/delete/{id}', [
            'as'=>'comment.delete',
            'uses'=>'CommentController@delete'
        ]);

        

    });
//follow
    Route::prefix('follow')->group(function(){
        //index
        Route::get('/',[
            'as' => 'follow.index',
            'uses'=>'FollowController@index'
        ]);
        Route::get('/create', [
            'as'=>'follow.create',
            'uses'=>'FollowController@create'
        ]);
        Route::post('/store',[
            'as' => 'follow.store',
            'uses' => 'FollowController@store'
        ]);
        Route::get('/edit{id}', [
            'as'=> 'follow.edit',
            'uses'=> 'FollowController@edit'
        ]);
        Route::post('/update/{id}',[
            'as'=>'follow.update',
            'uses'=>'FollowController@update'
        ]);
        Route::get('/delete/{id}', [
            'as'=>'follow.delete',
            'uses'=>'FollowController@delete'
        ]);

        

    });
//user
    Route::prefix('user')->group(function(){
        //index
        Route::get('/',[
            'as' => 'user.index',
            'uses'=>'UserController@index'
        ]);
        Route::get('/create', [
            'as'=>'user.create',
            'uses'=>'UserController@create'
        ]);
        Route::post('/store',[
            'as' => 'user.store',
            'uses' => 'UserController@store'
        ]);
        Route::get('/edit{id}', [
            'as'=> 'user.edit',
            'uses'=> 'UserController@edit'
        ]);
        Route::post('/update/{id}',[
            'as'=>'user.update',
            'uses'=>'UserController@update'
        ]);
        Route::get('/delete/{id}', [
            'as'=>'user.delete',
            'uses'=>'UserController@delete'
        ]);

        

    });
//ATTENDANCE
    Route::prefix('attendance')->group(function(){
        //index
        Route::get('/',[
            'as' => 'attendance.index',
            'uses'=>'AttendanceController@index'
        ]);
         Route::post('/search',[
            'as' => 'attendance.search',
            'uses'=>'AttendanceController@search'
        ]);
        Route::get('/create', [
            'as'=>'user.create',
            'uses'=>'UserController@create'
        ]);
        Route::post('/store',[
            'as' => 'user.store',
            'uses' => 'UserController@store'
        ]);
        Route::get('/edit{id}', [
            'as'=> 'user.edit',
            'uses'=> 'UserController@edit'
        ]);
        Route::post('/update/{id}',[
            'as'=>'user.update',
            'uses'=>'UserController@update'
        ]);
        Route::get('/delete/{id}', [
            'as'=>'user.delete',
            'uses'=>'UserController@delete'
        ]);

        

    });
//TEACHER
    Route::prefix('teacher')->group(function(){
        //index
        Route::get('/',[
            'as' => 'teacher.index',
            'uses'=>'TeacherController@index'
        ]);
        Route::get('/create', [
            'as'=>'teacher.create',
            'uses'=>'TeacherController@create'
        ]);
        Route::post('/store',[
            'as' => 'teacher.store',
            'uses' => 'TeacherController@store'
        ]);
        Route::get('/edit{id}', [
            'as'=> 'teacher.edit',
            'uses'=> 'TeacherController@edit'
        ]);
        Route::post('/update/{id}',[
            'as'=>'teacher.update',
            'uses'=>'TeacherController@update'
        ]);
        Route::get('/delete/{id}', [
            'as'=>'teacher.delete',
            'uses'=>'TeacherController@delete'
        ]);

        

    });
//STUDENT
    Route::prefix('student')->group(function(){
        //index
        Route::get('/',[
            'as' => 'student.index',
            'uses'=>'StudentController@index'
        ]);
        Route::get('/create', [
            'as'=>'student.create',
            'uses'=>'StudentController@create'
        ]);
        Route::post('/store',[
            'as' => 'student.store',
            'uses' => 'StudentController@store'
        ]);
        Route::get('/edit{id}', [
            'as'=> 'student.edit',
            'uses'=> 'StudentController@edit'
        ]);
        Route::post('/update/{id}',[
            'as'=>'student.update',
            'uses'=>'StudentController@update'
        ]);
        Route::get('/delete/{id}', [
            'as'=>'student.delete',
            'uses'=>'StudentController@delete'
        ]);

        

    });
//Subject
    Route::prefix('subject')->group(function(){
        //index
        Route::get('/',[
            'as' => 'subject.index',
            'uses'=>'SubjectController@index'
        ]);
        Route::get('/create', [
            'as'=>'subject.create',
            'uses'=>'SubjectController@create'
        ]);
        Route::post('/store',[
            'as' => 'subject.store',
            'uses' => 'SubjectController@store'
        ]);
        Route::get('/edit{id}', [
            'as'=> 'subject.edit',
            'uses'=> 'SubjectController@edit'
        ]);
        Route::post('/update/{id}',[
            'as'=>'subject.update',
            'uses'=>'SubjectController@update'
        ]);
        Route::get('/delete/{id}', [
            'as'=>'subject.delete',
            'uses'=>'SubjectController@delete'
        ]);

        

        

    });
//List student
   Route::prefix('subject')->group(function(){
        Route::get('/{id}/list_student/',[
            'as' => 'list_student.index',
            'uses'=>'ListStudentController@index'
        ]);
        Route::get('/{id}/list_student/create', [
            'as'=>'list_student.create',
            'uses'=>'ListStudentController@create'
        ]);
        Route::get('/{id}/list_student/store/{id_subject}',[
            'as' => 'list_student.store',
            'uses' => 'ListStudentController@store'
        ]);
        Route::get('/{id}/list_student/edit/{id1}', [
            'as'=> 'list_student.edit',
            'uses'=> 'ListStudentController@edit'
        ]);
        Route::post('/{id}/list_student/update/{id1}',[
            'as'=>'list_student.update',
            'uses'=>'ListStudentController@update'
        ]);
        Route::get('/{id}/list_student/delete/{id1}', [
            'as'=>'list_student.delete',
            'uses'=>'ListStudentController@delete'
        ]);

    });
    
});

