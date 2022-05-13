@extends('layouts.admin')

@section('title')
    <title>Tài khoản</title>
@endsection

@section('css')
<link href="{{ asset('admins/css/image.css') }}" rel="stylesheet" />    
@endsection

@section('content')
<div class="content-wrapper">
<form class="" action="{{ route('user.update',['id'=>$Users->id]) }}" method="POST" enctype="multipart/form-data" >
    @csrf
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                
                <div class="col-md-6 mt-3">
                    
                        <div class="form-group" >
                            <label >Tên tài khoản</label>
                            <input class="form-control @error('name') is-invalid @enderror " readonly type="text" name="name" id="name" value="{{$Users->name}}" >
                            @error('name')
							    <div class="alert alert-danger alert-style">{{ $message }}</div>
							@enderror
                        </div>
                        <div class="form-group">
                            <label >Địa chỉ email</label>
                            <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" id="email" value="{{$Users->email}}">
                            @error('email')
							    <div class="alert alert-danger alert-style">{{ $message }}</div>
							@enderror
                        </div>
                        <div class="form-group">
                            <label >Password</label>
                            <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" id="password"  value="{{$Users->password}}">
                            @error('password')
							    <div class="alert alert-danger alert-style">{{ $message }}</div>
							@enderror
                        </div>
                       
                        <button class="btn btn-success" name="create">Sửa</button>
                   
                </div>
                
            </div>
        </div>    
    </div>
</form>
</div>   
@endsection

@section('js')
<script>
    // Hiển thị ảnh preview (xem trước) khi người dùng chọn Ảnh
    const reader = new FileReader();
    const fileInput = document.getElementById("image_name");
    const img = document.getElementById("preview-img");
    reader.onload = e => {
      img.src = e.target.result;
    }
    fileInput.addEventListener('change', e => {
      const f = e.target.files[0];
      reader.readAsDataURL(f);
    })
  </script>
@endsection