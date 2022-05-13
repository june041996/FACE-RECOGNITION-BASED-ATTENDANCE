@extends('layouts.admin')

@section('title')
    <title>Giảng Viên</title>
@endsection

@section('content')
<div class="content-wrapper">
<form class="" action="{{ route('subject.store') }}" method="POST" >
    @csrf
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                
                <div class="col-md-6 mt-3">
                    
                        <div class="form-group">
                            <label >Tên </label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name1" id="name1" value="{{old('name')}}">
                            @error('name')
                                <div class="alert alert-danger alert-style">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label >Thời gian bắt đầu </label>
                            <input class="form-control @error('time_start') is-invalid @enderror" type="time" name="time_start" id="time_start" value="{{old('time_start')}}">
                            @error('time_start')
                                <div class="alert alert-danger alert-style">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label >Thời gian kết thúc </label>
                            <input class="form-control @error('time_end') is-invalid @enderror" type="time" name="time_end" id="time_end" value="{{old('time_end')}}">
                            @error('time_end')
                                <div class="alert alert-danger alert-style">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label >Ngày bắt đầu </label>
                            <input class="form-control @error('day_start') is-invalid @enderror" type="date" name="day_start" id="day_start" value="{{old('day_start')}}">
                            @error('time_end')
                                <div class="alert alert-danger alert-style">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label >Ngày kết thúc </label>
                            <input class="form-control @error('day_end') is-invalid @enderror" type="date" name="day_end" id="day_end" value="{{old('day_end')}}">
                            @error('day_end')
                                <div class="alert alert-danger alert-style">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label >Ngày trong tuần </label>
                            <select class="form-control @error('day_end') is-invalid @enderror" id="DoW" name="DoW">
                              <option value="Monday">Monday</option>
                              <option value="Tuesday">Tuesday</option>
                              <option value="Wednesday">Wednesday</option>
                              <option value="Thursday" >Thursday</option>
                              <option value="Friday" >Friday</option>
                              <option value="Saturday" >Saturday</option>
                              <option value="Sunday" >Sunday</option>
                            </select>
                            @error('DoW')
                                <div class="alert alert-danger alert-style">{{ $message }}</div>
                            @enderror
                        </div>

                        <div >
                            <label for="">Chọn giảng viên</label>
                            <select name="name" id="name" class="form-control  @error('name') is-invalid @enderror" >
                                <option value="{{old('name')}}" >{{old('name')}}</option>
                                @foreach($Teacher as $tag)
                                    <option value="{{$tag->name}}" >{{$tag->name}}</option>
                                @endforeach 
                            </select>
                            @error('name')
                                <div class="alert alert-danger alert-style">{{ $message }}</div>
                            @enderror
                        </div>

                                            
 
                        <button class="btn btn-success" name="create">Tạo</button>
                   
                </div>
                
            </div>
        </div>    
    </div>
</form>
</div>   
@endsection
