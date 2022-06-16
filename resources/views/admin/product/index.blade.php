@extends('Admin.layout.master')
@section('content')
<style>
  .rating .active{
    color: #ff9705 !important;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sản phẩm - Danh sách</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Trang chủ</a></li>
              <li class="breadcrumb-item active">Sản phẩm - Danh sách</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          @if(Session::has('create_product_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Thành công !</strong> {{Session::get('create_product_success')}}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
          @if(Session::has('edit_product_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Thành công !</strong> {{Session::get('edit_product_success')}}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
          @if(Session::has('delete_product_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Thành công !</strong> {{Session::get('delete_product_success')}}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
            <table class="table table-hover table-striped" id="dataTable">
                <thead class="thead-dark">
                    <th>ID</th>
                    <th>Sản phẩm</th>
                    <th>Loại sản phẩm</th>
                    <th>Ảnh</th>
                    <th style="width: 11%;">Trạng thái</th>
                    <th>Nổi bật</th>
                    <th style="width: 12%;">Thao tác</th>
                </thead>
                <tbody>
                  @if(isset($products))
                    @foreach($products as $pro)
                      <tr>
                        <td>{{$pro->id}}</td>
                        <td>
                          <b>{{$pro->pro_name}}</b><br/>
                          <ul style="padding:0px">
                            <li>Số lượng: {{$pro->pro_number}}</li>
                            <li>Giá: {{number_format($pro->pro_price,0,",",".")}} VNĐ</li>
                            @if($pro->pro_sale)
                            <li>Đang giảm giá ( -{{$pro->pro_sale}}% )</li>
                            @else
                            <li>Không giảm giá</li>
                            @endif
                            <li>
                              <?php
                              $point= 0;
                              if($pro->pro_number_of_reviewers>0){
                                $point= round($pro->pro_total_star/$pro->pro_number_of_reviewers);
                              }
                              ?>
                              Đánh giá: <span class="rating">
                                @for($i=1; $i <= 5; $i++)
                                  <i class="fa fa-star {{ $i<=$point ? 'active':''}}" style="color:#999"></i>
                                @endfor
                                @if($pro->pro_number_of_reviewers>0)
                                  {{$point}} sao
                                @else
                                  Chưa đánh giá
                                @endif
                              </span>
                            </li>
                          </ul>
                        </td>
                        <td>{{$pro->Category->c_name}}</td>
                        <td>
                          @if($pro->pro_image)
                            <img style="width:80px;height:80px" src="{{asset('/public/upload/pro_image/'.$pro->pro_image)}}" alt="No Avatar"/>
                          @else
                          <img style="width:80px;height:80px" src="{{asset('noimg.png')}}" alt="No Avatar"/>
                          @endif
                        </td>
                        <td style="text-align: center"><a href="{{route('admin.product.handle',['status',$pro->id])}}" class="badge badge-{{($pro->pro_status==1)?"success":"danger"}}">{{($pro->pro_status==1)?"Công khai":"Riêng tư"}}</a></td>
                        <td style="text-align: center"><a href="{{route('admin.product.handle',['hot',$pro->id])}}" class="badge badge-{{($pro->pro_hot==1)?"success":"secondary"}}">{{($pro->pro_hot==1)?"Có":"Không"}}</a></td>
                        <td>
                          <a href="{{route('admin.product.edit',$pro->id)}}" class="btn btn-success btn-circle"><i class="fas fa-edit"></i></a>
                          <a href="{{route('admin.product.handle',['delete',$pro->id])}}" data-id="{{$pro->id}}" class="btn_delete_sweet btn btn-danger btn-circle"><i class="fas fa-trash-alt"></i></a>
                        </td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
      </div>
      <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a class="btn btn-danger" href="{{route('admin.product.create')}}" class="nav-link {{(request()->is('admin/product/create'))?"active":""}}">Thêm</a>
            </div>
        </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
 <!-- /.content-wrapper -->
@endsection
@section('javascript')
<script>
  $(document).ready( function () {
    $('#dataTable').DataTable({
      "order": [[ 0, "desc" ]],
      "language" : {
        "decimal":        "",
        "emptyTable":     "Không có dữ liệu hiển thị trong bảng",
        "info":           "Đang hiển thị bản ghi _START_ đến _END_ trên _TOTAL_ bản ghi",
        "infoEmpty":      "Hiển thị 0 đến 0 của 0 bản ghi",
        "infoFiltered":   "(đã lọc từ _MAX_ bản ghi)",
        "infoPostFix":    "",
        "thousands":      ",",
        "lengthMenu":     "Hiển thị _MENU_ bản ghi",
        "loadingRecords": "Đang tải...",
        "processing":     "Đang xử lý...",
        "search":         "Tìm kiếm:",
        "zeroRecords":    "Không có bản ghi nào được tìm thấy",
        "paginate": {
            "first":      "Đầu",
            "last":       "Cuối",
            "next":       "Tiếp",
            "previous":   "Trước"
        },
        "aria": {
            "sortAscending":  ": activate to sort column ascending",
            "sortDescending": ": activate to sort column descending"
        }
      }
    });
  });
</script>
<script>
  $(".btn_delete_sweet").click(function(e)
  {
    e.preventDefault();
    url = $(this).attr('href');
    id= $(this).attr('data-id');
    swal({
            title: "Bạn có chắc chắn?",
            text: "Bạn có chắc chắn muốn xóa sản phẩm ID="+id+" không ? Điều này sẽ ảnh hưởng đến liên kết dữ liệu !!",
            icon: "info",
            buttons: ["Không","Có"],
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                swal("Thành công","Hệ thống chuẩn bị xóa sản phẩm mang ID ="+id+" !",'success').then(function() {
                window.location.href = url;
                });
            }
        });
  });
</script>
@endsection
