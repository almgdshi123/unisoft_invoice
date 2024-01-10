@extends('layouts.master')
@section('css')
    <!---Internal  Prism css-->
    <link href="{{ URL::asset('assets/plugins/prism/prism.css') }}" rel="stylesheet">
    <!---Internal Input tags css-->
    <link href="{{ URL::asset('assets/plugins/inputtags/inputtags.css') }}" rel="stylesheet">
    <!--- Custom-scroll -->
    <link href="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
@endsection
@section('title')
    تفاصيل العميل
@stop
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">قائمة العملاء</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    تفاصيل العميل</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif



    @if (session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif



    <!-- row opened -->
    <div class="row row-sm">

        <div class="col-xl-12">
            <!-- div -->
            <div class="card mg-b-20" id="tabs-style2">
                <div class="card-body">
                    <div class="text-wrap">
                        <div class="example">
                            <div class="panel panel-primary tabs-style-2">
                                <div class=" tab-menu-heading">
                                    <div class="tabs-menu1">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs main-nav-line">
                                            <li><a href="#tab4" class="nav-link active" data-toggle="tab">معلومات
                                                    العميل</a></li>
                                            <li><a href="#tab5" class="nav-link" data-toggle="tab">حالات الفواتير</a></li>
                                            <li><a href="#tab6" class="nav-link" data-toggle="tab">حالات الدفع الفواتير</a></li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body main-content-body-right border">
                                    <div class="tab-content">


                                        <div class="tab-pane active" id="tab4">
                                            <div class="table-responsive mt-15">

                                                <table class="table table-striped" style="text-align:center">
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">رقم العميل</th>
                                                            <td>{{$customers->id}}</td>
                                                            <th scope="row">اسم العميل</th>
                                                            <td>{{$customers->name_customer}}</td>
                                                            <th scope="row">رقم هاتف العميل</th>
                                                            <td>{{$customers->phone_customer}}</td>
                                                         
                                                            
                                                           
                                                        </tr>

                                                     


                                                        <tr>
                                                           
                                                            <th scope="row">رقم حساب العميل</th>
                                                            <td>{{$box->id}}</td>
                                                          
                                                            <th scope="row">  علية في الحساب </th>
                                                            <td>{{$box->Total}}</td>
                                                            <th scope="row">الحالة الحالية</th>

                                                            @if ($customers->Value_Status == 1)
                                                                    <td><span
                                                                            class="badge badge-pill badge-success">حساب نشط</span>
                                                                    </td>
                                                                @else
                                                                    <td><span
                                                                            class="badge badge-pill badge-danger">حساب غير نشط</span>
                                                                    </td>
                                                            
                                                                @endif
                                                        </tr>

                                                        <tr>
                                                            <th scope="row"> منشى الحساب</th>
                                                            <td>{{$customers->Created_by}}</td>
                                                            <th scope="row"> تاريخ الانشاء</th>
                                                            <td>{{$customers->created_at}}</td>
                                                            <th scope="row">ملاحظات</th>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>

                                        <div class="tab-pane" id="tab5">
                                            <div class="table-responsive mt-15">
                                                <table class="table center-aligned-table mb-0 table-hover"
                                                    style="text-align:center">
                                                    <thead>
                                                        <tr class="text-dark">
                                                            <th>#</th>
                                                            <th>رقم العملية</th>
                                                            <th>رقم الفاتورة</th>
                                                            <th>الملبغ المستحق</th>
                                                            <th>المبلغ في الحساب</th>
                                                            <th>رقم الحساب</th>

                                                            <th>تاريخ لاضافة </th>
                                                            <th>المستخدم</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 0; ?>
                                                        @foreach($dep as $d )

                                                            <?php $i++; ?>
                                                            <tr>
                                                                <td>{{ $i }}</td>
                                                                <td>{{$d->id}}</td>
                                                                <td>{{$d->id_Invoice}}</td>
                                                               
                                                                <td>{{$d->Value_VAT}}</td>
                                                                <td>{{$d->Total}}</td>
                                                                <td>{{$d->id_box}}</td>
                                                                <td>{{$d->created_at}}</td>
                                                                <td>{{$d->Created_by}}</td>
 
                                                            </tr>
                                                            
                                                            @endforeach
                                                    </tbody>
                                                </table>


                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab6">
                                            <div class="table-responsive mt-15">
                                                <table class="table center-aligned-table mb-0 table-hover"
                                                style="text-align:center">
                                                <thead>
                                                    <tr class="text-dark">
                                                        <th>#</th>
                                                        <th>رقم العملية</th>
                                                        <th>رقم الفاتورة</th>
                                                        <th>الملبغ الايصال</th>
                                                        <th>المبلغ في الحساب</th>
                                                        <th>رقم الحساب</th>

                                                        <th>تاريخ لاضافة </th>
                                                        <th>المستخدم</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 0; ?>
                                                    @foreach($dep1 as $d )

                                                        <?php $i++; ?>
                                                        <tr>
                                                            <td>{{ $i }}</td>
                                                            <td>{{$d->id}}</td>
                                                            <td>{{$d->id_Invoice}}</td>
                                                           
                                                            <td>{{$d->Value_VAT}}</td>
                                                            <td>{{$d->Total}}</td>
                                                            <td>{{$d->id_box}}</td>
                                                            <td>{{$d->created_at}}</td>
                                                            <td>{{$d->Created_by}}</td>

                                                        </tr>
                                                        
                                                        @endforeach
                                                </tbody>
                                            </table>


                                            </div>
                                        </div>


                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /div -->
        </div>

    </div>
    <!-- /row -->

    <!-- delete -->
    <div class="modal fade" id="delete_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف المرفق</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('delete_file') }}" method="post">

                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p class="text-center">
                        <h6 style="color:red"> هل انت متاكد من عملية حذف المرفق ؟</h6>
                        </p>

                        <input type="hidden" name="id_file" id="id_file" value="">
                        <input type="hidden" name="file_name" id="file_name" value="">
                        <input type="hidden" name="invoice_number" id="invoice_number" value="">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal Jquery.mCustomScrollbar js-->
    <script src="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <!-- Internal Input tags js-->
    <script src="{{ URL::asset('assets/plugins/inputtags/inputtags.js') }}"></script>
    <!--- Tabs JS-->
    <script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
    <script src="{{ URL::asset('assets/js/tabs.js') }}"></script>
    <!--Internal  Clipboard js-->
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.js') }}"></script>
    <!-- Internal Prism js-->
    <script src="{{ URL::asset('assets/plugins/prism/prism.js') }}"></script>

    <script>
        $('#delete_file').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id_file = button.data('id_file')
            var file_name = button.data('file_name')
            var invoice_number = button.data('invoice_number')
            var modal = $(this)

            modal.find('.modal-body #id_file').val(id_file);
            modal.find('.modal-body #file_name').val(file_name);
            modal.find('.modal-body #invoice_number').val(invoice_number);
        })
    </script>

    <script>
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>

@endsection
