@extends('layouts.admin')

@section('title')
    <title>Giảng Viên</title>
@endsection

@section('content')
<div class="content-wrapper">
<form class="" action="{{ route('teacher.store') }}" method="POST" >
    @csrf
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                
                <div class="col-md-6 mt-3">
                    
                        <div class="form-group">
                            <label >Tên </label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{old('name')}}">
                            @error('name')
                                <div class="alert alert-danger alert-style">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label >Số điện thoại</label>
                            <input class="form-control @error('email') is-invalid @enderror" type="number" name="phone_number" id="phone_number" value="{{old('phone_number')}}">
                            @error('phone_number')
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
