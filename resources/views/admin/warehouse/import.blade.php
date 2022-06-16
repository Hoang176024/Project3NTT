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
            <h1>Kho hàng - Nhập hàng</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Trang chủ</a></li>
              <li class="breadcrumb-item active">Kho hàng - Nhập hàng</li>
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
          @if(Session::has('import_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Thành công !</strong> {{Session::get('import_success')}}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
          @if(Session::has('import_error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Thất bại !</strong> {{Session::get('import_error')}}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
            <table class="table table-hover table-striped" id="dataTable">
                <thead class="thead-dark">
                    <th>ID</th>
                    <th style="width:36%">Tên sản phẩm</th>
                    <th>Loại sản phẩm</th>
                    <th>Ảnh</th>
                    <th>Số lượng</th>
                    <th style="width: 10%;">Thao tác</th>
                </thead>
                <tbody>
                  @if(isset($products))
                    @foreach($products as $pro)
                      <tr>
                        <td>{{$pro->id}}</td>
                        <td>
                          <b>{{$pro->pro_name}}</b><br/>
                        </td>
                        <td>{{$pro->Category->c_name}}</td>
                        <td>
                          @if($pro->pro_image)
                            <img style="width:80px;height:80px" src="{{asset('public/upload/pro_image/'.$pro->pro_image)}}" alt="No Avatar"/>
                          @else
                          <img style="width:80px;height:80px" src="{{asset('noimg.png')}}" alt="No Avatar"/>
                          @endif
                        </td>
                        <td style="text-align: center">{{number_format($pro->pro_number,0,',','.')}}</td>
                        <td style="text-align: center">
                          <a href="{{route('admin.warehouse.import.product',$pro->id)}}" data-name="{{$pro->pro_name}}" class="btn_import_product btn btn-success btn-circle"><i class="fa fa-plus-circle"></i></a>
                        </td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
 <!-- /.content-wrapper -->
 {{-- modal import products --}}
 <div class="modal fade" id="modal_import_product" tabindex="-1" role="dialog" aria-labelledby="modal_import_product" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Nhập sản phẩm " <span class="name_product_import"></span> "</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="#" class="form_import_product">
                <div class="form-group">
                    Số lượng
                    <input type="number" name="product_number" class="form-control"/>
                </div>
                <div class="form-group">
                            <label>Nhà kho</label>
                            <select id="warehouse" name="warehouse_id" class="form-control select2">
                                @foreach($warehouses as $warehouse)
                                <option value="{{$warehouse->id}}">{{$warehouse->wh_name}}</option>
                                @endforeach
                            </select>
                        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          <button type="button" class="btn btn-primary btn_accept_import">Đồng ý</button>
        </div>
      </div>
    </div>
  </div>
 {{-- end modalimport products --}}
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
  $(".btn_import_product").click(function(e)
  {
    e.preventDefault();
    url = $(this).attr('href');
    name= $(this).attr('data-name');
    $(".name_product_import").text(name);
    $(".form_import_product").attr("action",url);
    $("#modal_import_product").modal('show');
  });
  $(".btn_accept_import").click(function(e)
  {
    e.preventDefault();
    $(".form_import_product").submit();
  });
</script>
@endsection