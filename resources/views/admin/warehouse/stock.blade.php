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
            <h1>Kho hàng</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Trang chủ</a></li>
              <li class="breadcrumb-item active">Kho hàng </li>
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
            <table class="table table-hover table-striped" id="dataTable">
                <thead class="thead-dark">
                    <th>ID</th>
                    <th>Nhà kho</th>
                    <th style="width:28%">Tên sản phẩm</th>
                    <th>Số lượng hàng</th>
                </thead>
                <tbody>
                    @foreach($warehouses as $pro)
                      <tr>
                        <td>{{$pro->id}}</td>
                        <td> {{$pro->wh_name}}</td>
                        <td>
                          @foreach($pro->Product as $prod)
                            <ul>{{isset($prod->pro_name)?$prod->pro_name:"Đã bị xóa"}}</ul>
                          @endforeach
                        </td>
                        <td style="text-align: left">
                          @foreach($pro->Product as $prod)
                            <ul>{{number_format($prod->pivot->quantity,0,',','.')}} sản phẩm</ul>
                          @endforeach
                        </td>
                       @endforeach
                      </tr>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a class="btn btn-danger" href="{{route('admin.warehouse.create')}}" class="nav-link {{(request()->is('admin/warehouse/create'))?"active":""}}">Thêm kho</a>
            </div>&nbsp&nbsp&nbsp&nbsp&nbsp
            <!--
            <div class="btn-group me-2">
                <a class="btn_import_product btn btn-success" href="{{route('admin.warehouse.import.WhPro')}}">Thêm hàng</a>
            </div>&nbsp&nbsp&nbsp&nbsp&nbsp -->
            <div class="btn-group me-2">
                <a class="btn_transfer_product btn btn-primary" href="{{route('admin.warehouse.transfer')}}">Chuyển hàng</a>
            </div>
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
 <!-- /.content-wrapper -->
 <!--
 {{-- modal import products --}}
 <div class="modal fade" id="modal_import_product" tabindex="-1" role="dialog" aria-labelledby="modal_import_product" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="#" class="form_import_product">
            <div class="form-group">
                            <label>Sản phẩm</label>
                            <select id="product" name="product_id" class="form-control select2">
                                @foreach($products as $product)
                                <option value="{{$product->id}}">{{$product->pro_name}}</option>
                                @endforeach
                            </select>
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
-->
 {{-- modal transfer products --}}
 <div class="modal fade" id="modal_transfer_product" tabindex="-1" role="dialog" aria-labelledby="modal_transfer_product" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="#" class="form_transfer_product">
            <div class="form-group">
                <label>Sản phẩm</label>
                  <select id="product" name="product_id" class="form-control select2">
                    @foreach($products as $product)
                    <option value="{{$product->id}}">{{$product->pro_name}}</option>
                    @endforeach
                  </select>
            </div>
            <div class="form-group">
              <label>Nhà kho gốc</label>
                <select id="warehouse1" name="warehouse_id_1" class="form-control select2">
                  @foreach($warehouses as $warehouse)
                  <option value="{{$warehouse->id}}">{{$warehouse->wh_name}}</option>
                  @endforeach
                </select>
            </div>
            <div class="form-group">
              <label>Nhà kho đích</label>
                <select id="warehouse2" name="warehouse_id_2" class="form-control select2">
                  @foreach($warehouses as $warehouse)
                  <option value="{{$warehouse->id}}">{{$warehouse->wh_name}}</option>
                  @endforeach
                </select>
            </div>
            <div class="form-group">
                  Số lượng
              <input type="number" name="product_num" class="form-control"/>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          <button type="button" class="btn btn-primary btn_accept_transfer">Đồng ý</button>
        </div>
      </div>
    </div>
  </div>
 {{-- end modal transfer products --}}
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
<!--
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
-->
<script>
  $(".btn_transfer_product").click(function(e)
  {
    e.preventDefault();
    url = $(this).attr('href');
    name= $(this).attr('data-name');
    $(".name_product_transfer").text(name);
    $(".form_transfer_product").attr("action",url);
    $("#modal_transfer_product").modal('show');
  });
  $(".btn_accept_transfer").click(function(e)
  {
    e.preventDefault();
    $(".form_transfer_product").submit();
  });
</script>
@endsection