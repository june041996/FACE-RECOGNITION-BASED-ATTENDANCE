@extends('layouts.admin')

@section('title')
    <title>Giảng viên</title>
@endsection

@section('css')
<link href="{{ asset('admins/css/image.css') }}" rel="stylesheet" />    
@endsection

@section('content')
<div class="content-wrapper">
<form class="" action="{{ route('teacher.update',['id'=>$Teacher->id]) }}" method="POST" enctype="multipart/form-data" >
    @csrf
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                
                <div class="col-md-6 mt-3">
                    
                        <div class="form-group" >
                            <label >Tên </label>
                            <input class="form-control @error('name') is-invalid @enderror "     type="text" name="name" id="name" value="{{$Teacher->name}}" >
                            @error('name')
							    <div class="alert alert-danger alert-style">{{ $message }}</div>
							@enderror
                        </div>
                        <div class="form-group">
                            <label >Số điện thoại</label>
                            <input class="form-control @error('phone_number') is-invalid @enderror" type="number" name="phone_number" id="phone_number" value="{{$Teacher->phone_number}}">
                            @error('phone_number')
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