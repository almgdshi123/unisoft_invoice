@extends('layouts.master')
@section('title')
    قائمة العملاء - يوني سوفت للادارة القانونية

@stop
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الاعدادات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    العملاء</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')



    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <!-- row -->
    <div class="row">

        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="col-sm-1 col-md-2">
                        @can('اضافة عميل')
                            <a class="btn btn-primary btn-sm" href="{{ route('customers.create') }}">اضافة عميل</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive ">
                        <table class="table table-hover" id="example1" data-page-length='50' style=" text-align: center;">
                            <thead>
                                <tr>
                                    <th class="wd-10p border-bottom-0">#</th>
                                    <th class="wd-15p border-bottom-0">اسم العميل</th>
                                    <th class="wd-20p border-bottom-0">رقم الهاتف</th>
                                    <th class="wd-15p border-bottom-0">حالة العميل</th>
                                    <th class="wd-15p border-bottom-0">ملاحضات </th>
                                    <th class="wd-10p border-bottom-0">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $cust)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $cust->name_customer }}</td>
                                        <td>{{ $cust->phone_customer }}</td>
                                        <td>
                                            @if ($cust->Value_Status == 1)
                                                <span class="label text-success d-flex">
                                                    <div class="dot-label bg-success ml-1"></div>مفعل
                                                </span>
                                            @else
                                                <span class="label text-danger d-flex">
                                                    <div class="dot-label bg-danger ml-1"></div>غير مفعل
                                                </span>
                                            @endif
                                        </td>
                                        @if ($cust->note_customer == null)
                                            <td> لايوجد وصف</td>
                                        @else
                                            <td> {{ $cust->note_customer }}</td>
                                        @endif
                                        <td>
                                            <div class="dropdown">
                                                <button aria-expanded="false" aria-haspopup="true"
                                                    class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                    type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                                                <div class="dropdown-menu tx-13">
                                                    @can('تعديل عميل')
                                                        <a class="dropdown-item"
                                                            href="{{ route('customers.edit', $cust->id) }}"><i
                                                                class="text-warning fas fa-sync-alt"></i>&nbsp;&nbsp;تعديل
                                                            العميل</a>
                                                    @endcan
                                                    <a class="dropdown-item"
                                                    href="{{ url('customersDetails') }}/{{ $cust->id }}"><i
                                                        class="text-primary fas fa-tv"></i>&nbsp;&nbsp;تتفاصيل
                                                    حساب العميل</a>
                                                    @can('حذف عميل')
                                                        <a class="dropdown-item" data-effect="effect-scale"
                                                            data-id="{{ $cust->id }}"
                                                            data-username="{{ $cust->name_customer }}" data-toggle="modal"
                                                            href="#modaldemo8"><i
                                                                class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                            عميل</a>
                                                    @endcan



                                                </div>
                                            </div>

                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="modaldemo8">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">حذف عميل</h6><button aria-label="Close" class="close" data-dismiss="modal"
                            type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{ route('customers.destroy', 'test') }}" method="post">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <p>هل انت متاكد من عملية الحذف ؟</p><br>
                            <input type="hidden" name="id" id="id" value="">
                            <input class="form-control" name="username" id="username" type="text" readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-danger">تاكيد</button>
                        </div>
                </div>
                </form>
            </div>
        </div>

    </div>
    <!-- row closed -->
    <!-- Basic modal -->


    </div>
    <!-- Container closed -->
    </div>


    <!-- main-content closed -->
@endsection

@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <!-- Internal Modal js-->
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>

    <script>
        $('#modaldemo8').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var username = button.data('username')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #username').val(username);
        })
    </script>

@endsection
