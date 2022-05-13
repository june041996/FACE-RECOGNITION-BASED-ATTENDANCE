@extends('layouts.admin')

@section('title')
    <title>HOME</title>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="content mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                              <div class="inner">
                                <h3>{{$total_teacher}} </h3>
                
                                <p>Giảng viên</p>
                                
                              </div>
                              
                              <div class="icon">
                                <i class="ion ion-bag"></i>
                              </div>
                              <a href="{{route('comics.index')}}" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                          </div>
                          <!-- ./col -->
                          <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                              <div class="inner">
                                <h3>{{$total_student}}</h3>
                
                                <p>Sinh viên</p>
                              </div>
                              <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                              </div>
                              <a href="{{route('comics.index')}}" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                          </div>
                          <!-- ./col -->
                          <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                              <div class="inner">
                                <h3>{{$total_subject}}</h3>
                
                                <p>Môn học</p>
                              </div>
                              <div class="icon">
                                <i class="ion ion-person-add"></i>
                              </div>
                              <a href="{{route('comics.index')}}" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                          </div>
                          <!-- ./col -->

                           <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                              <div class="inner">
                                <h3>{{$total_user}}</h3>
                
                                <p>Tài khoản</p>
                              </div>
                              <div class="icon">
                                <i class="ion ion-person-add"></i>
                              </div>
                              <a href="{{route('comics.index')}}" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                          </div>
                         
                        
                    </div>
                </div>
            </div>
        </div>    
    </div>   
</div>   
@endsection