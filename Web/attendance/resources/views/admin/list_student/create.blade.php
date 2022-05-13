@extends('layouts.admin')

@section('title')
    <title>Sinh viên</title>
@endsection
@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@endsection
@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- <div class="col-md-12">
                    <a href="{{ route('comment.create') }}" class="btn btn-success ">Thêm bình luận</a>
                </div>

 -->             
                <div class="col-sm-6">
                  <ol class="breadcrumb ">
                    <li class="breadcrumb-item"><a href="{{route('list_student.index',['id'=>$Subject->id])}}">{{$Subject->name}}</a></li>
                  

                  </ol>
                </div>
                   <div class="col-md-12">
                    <table class="table table-bordered " id="myTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>MSSV</th>
                                
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Student as $itemComment)
                            <tr> 
                                    <td>{{ $itemComment->id }}</td>
                                    <td>{{ $itemComment->username }}</td>
                                    <td>{{ $itemComment->mssv }}</td>
                                    


                                    <td>
                                        <a href="{{ route('list_student.store',['id'=>$itemComment->mssv,'id_subject'=>$Subject->id]) }}" class="btn btn-warning">Thêm</a>
                                        

                                    </td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
    </div>   
</div>   
@endsection

@section('js')
<script>
  $(document).ready( function () {
  $('#myTable').DataTable({
      "bLengthChange": false,
      "language": {
          "lengthMenu": "Hiển thị _MENU_ dòng mỗi trang",
          "zeroRecords": "Không có kết quả tìm kiếm",
          "info": " _PAGE_ / _PAGES_",
          "infoEmpty": "Không có bảng ghi phù hợp",
          "infoFiltered": "(tìm kiếm từ _MAX_ bảng ghi)",
          "infoPostFix":    "",
          "thousands":      ",",
          "lengthMenu":     "Hiện _MENU_ hàng",
          "loadingRecords": "Loading...",
          "processing":     "Processing...",
          "search":         "Tìm kiếm:",
          "paginate": {
              "first":      "First",
              "last":       "Last",
              "next":       "Next",
              "previous":   "Previous"
          },
      },
      "lengthMenu": [[5, 25, 50, -1], [5, 25, 50, "All"]]
  }    
  );
} );
</script>
<script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="{{ asset('vendors\sweetAlert2\sweetalert2@11.js') }}"></script>
  <script>
    var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
        Swal.fire({
            icon: 'success',
            title: msg,
         })    
    }
  </script>
  <script src="{{ asset('admins\js\delete.js') }}"></script>
@endsection