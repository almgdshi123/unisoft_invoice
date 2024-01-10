@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!-- Internal Spectrum-colorpicker css -->
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

@section('title')
    تقرير الفواتير - يوني سوفت للادارة الفواتير
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">التقارير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تقرير
                الفواتير</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>خطا</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- row -->
<div class="row">

    <div class="col-xl-12">
        <div class="card mg-b-20">


            <div class="card-header pb-0">

                <form action="/Search_invoices" method="POST" role="search" autocomplete="off">
                    {{ csrf_field() }}


                    <div class="col-lg-3">
                        <label class="rdiobox">
                            <input checked name="rdio" type="radio" value="1" id="type_div"> <span>بحث بنوع
                                الفاتورة</span></label>
                    </div>


                    <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                        <label class="rdiobox"><input name="rdio" value="2" type="radio"
                                id="radio_1"><span>بحث برقم
                                الفاتورة
                            </span></label>
                    </div>
                    <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                        <label class="rdiobox"><input name="rdio" value="3" type="radio"
                                id="radio_2"><span>بحث بلقسم
                            </span></label>
                    </div><br><br>
                   


                    <div class="row">

                        <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type">
                            <p class="mg-b-10">تحديد نوع الفواتير</p><select class="form-control select2"
                                name="type" required>
                                <option value="{{ $type ?? 'حدد نوع الفواتير' }}" selected>
                                    {{ $type ?? 'حدد نوع الفواتير' }}
                                </option>

                                <option value="مدفوعة">الفواتير المدفوعة</option>
                                <option value="غير مدفوعة">الفواتير الغير مدفوعة</option>
                                <option value="مدفوعة جزئيا">الفواتير المدفوعة جزئيا</option>

                            </select>
                        </div><!-- col-4 -->


                        <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="invoice_number">
                            <p class="mg-b-10">البحث برقم الفاتورة</p>
                            <input type="text" class="form-control" id="invoice_number" name="invoice_number">

                        </div><!-- col-4 -->

                        <div class="col-lg-3" id="start_at">
                            <label for="exampleFormControlSelect1">من تاريخ</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div><input class="form-control fc-datepicker" value="{{ $start_at ?? '' }}"
                                    name="start_at" placeholder="YYYY-MM-DD" type="text">
                            </div><!-- input-group -->
                        </div>

                        <div class="col-lg-3" id="end_at">
                            <label for="exampleFormControlSelect1">الي تاريخ</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div><input class="form-control fc-datepicker" name="end_at"
                                    value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text">
                            </div><!-- input-group -->
                        </div>
                    </div>

                    <div class="row" id="btn_in">
                        <div class="col-sm-1 col-md-1">
                            <button class="btn btn-primary btn-block">بحث</button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="card-header pb-0">
                <form action="/Search_Section" method="POST" role="search" autocomplete="off">
                    {{ csrf_field() }}

                    <div class="row">

                        <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="Section_name">
                            <p class="mg-b-10">تحديد نوع القسم</p>
                            <select name="Section" class="form-control select  "
                                onclick="console.log($(this).val())" onchange="console.log('change is firing')">
                                <!--placeholder-->
                                <option value="" selected disabled>حدد القسم</option>
                             
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"> {{ $section->section_name }}</option>
                                @endforeach
                            </select>
                        </div><!-- col-4 -->
                        <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="product_name">
                            <p class="mg-b-10">تحديد نوع المنتج</p>
                            <select id="product" name="product" class="form-control select">
                            </select>
                        </div><!-- col-4 -->




                        <div class="col-lg-3" id="start_at_Section">
                            <label for="exampleFormControlSelect1">من تاريخ</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div><input class="form-control fc-datepicker" value="{{ $start_at ?? '' }}"
                                    name="start_at" placeholder="YYYY-MM-DD" type="text">
                            </div><!-- input-group -->
                        </div>

                        <div class="col-lg-3" id="end_at_Section">
                            <label for="exampleFormControlSelect1">الي تاريخ</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div><input class="form-control fc-datepicker" name="end_at"
                                    value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text">
                            </div><!-- input-group -->
                        </div>
                    </div><br>

                    <div class="row" id="btn_s">
                        <div class="col-sm-1 col-md-1">
                            <button class="btn btn-primary btn-block">بحث</button>
                        </div>
                    </div>
                </form>

            </div>




            <div class="card-body">
                <div class="table-responsive">
                    @if (isset($details))
                        <table id="example" class="table key-buttons text-md-nowrap" style=" text-align: center">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">رقم الفاتورة</th>
                                    <th class="border-bottom-0">تاريخ القاتورة</th>
                                    <th class="border-bottom-0">تاريخ الاستحقاق</th>
                                    <th class="border-bottom-0">المنتج</th>
                                    <th class="border-bottom-0">القسم</th>
                                    <th class="border-bottom-0">الخصم</th>
                                    <th class="border-bottom-0">نسبة الضريبة</th>
                                    <th class="border-bottom-0">قيمة الضريبة</th>
                                    <th class="border-bottom-0">الاجمالي</th>
                                    <th class="border-bottom-0">الحالة</th>
                                    <th class="border-bottom-0">ملاحظات</th>
                                    <th class="border-bottom-0">العمليات</th>


                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($details as $invoice)
                                    <?php $i++; ?>
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $invoice->invoice_number }} </td>
                                        <td>{{ $invoice->invoice_Date }}</td>
                                        <td>{{ $invoice->Due_date }}</td>
                                        <td>{{ $invoice->product }}</td>

                                        @if ($invoice->section != null)
                                            <td><a href="{{ url('InvoicesDetails') }}/{{ $invoice->id }}">
                                                    {{ $invoice->section->section_name }}</a>
                                            </td>
                                        @else
                                            <td>
                                                <a class="dropdown-item"
                                                    href="{{ url('InvoicesDetails') }}/{{ $invoice->id }}"><i
                                                        class="text-primary fas fa-tv"></i>&nbsp;&nbsp;تتفاصيل
                                                    الفاتورة</a>
                                            </td>
                                        @endif
                                        <td>{{ $invoice->Discount }}</td>
                                        <td>{{ $invoice->Rate_VAT }}</td>
                                        <td>{{ $invoice->Value_VAT }}</td>
                                        <td>{{ $invoice->Total }}</td>
                                        <td>
                                            @if ($invoice->Value_Status == 1)
                                                <span class="text-success">{{ $invoice->Status }}</span>
                                            @elseif($invoice->Value_Status == 2)
                                                <span class="text-danger">{{ $invoice->Status }}</span>
                                            @else
                                                <span class="text-warning">{{ $invoice->Status }}</span>
                                            @endif

                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button aria-expanded="false" aria-haspopup="true"
                                                    class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                    type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                                                <div class="dropdown-menu tx-13">
                                                    @can('تعديل الفاتورة')
                                                        <a class="dropdown-item"
                                                            href=" {{ url('edit_invoice') }}/{{ $invoice->id }}"><i
                                                                class="text-warning fas fa-sync-alt"></i>&nbsp;&nbsp;تعديل
                                                            الفاتورة</a>
                                                    @endcan
                                                    <a class="dropdown-item"
                                                        href="{{ url('InvoicesDetails') }}/{{ $invoice->id }}"><i
                                                            class="text-primary fas fa-tv"></i>&nbsp;&nbsp;تتفاصيل
                                                        الفاتورة</a>
                                                    @can('حذف الفاتورة')
                                                        <a class="dropdown-item" href="#"
                                                            data-invoice_id="{{ $invoice->id }}" data-toggle="modal"
                                                            data-target="#delete_invoice"><i
                                                                class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                            الفاتورة</a>
                                                    @endcan
                                                    @can('تغير حالة الدفع')
                                                        <form action="{{ route('Status_show', [$invoice->id]) }}"
                                                            method="get">
                                                            <input type="hidden" name="id_page" id="id_page" value="1">


                                                            <button type="submit" class="dropdown-item"><i
                                                                    class=" text-success fas
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    fa-money-bill"></i>&nbsp;&nbsp;تغير
                                                                حالة
                                                                الدفع</button>
                                                        </form>
                                                    @endcan
                                                    @can('ارشفة الفاتورة')
                                                        <a class="dropdown-item" href="#"
                                                            data-invoice_id="{{ $invoice->id }}" data-toggle="modal"
                                                            data-target="#Transfer_invoice"><i
                                                                class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل الي
                                                            الارشيف</a>
                                                    @endcan
                                                    @can('طباعةالفاتورة')
                                                        <a class="dropdown-item" href="Print_invoice/{{ $invoice->id }}"><i
                                                                class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                                                            الفاتورة
                                                        </a>
                                                    @endcan
                                                </div>
                                            </div>

                                        </td>

                                        <td>{{ $invoice->note }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- row closed -->
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

<!--Internal  Datepicker js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<!--Internal  pickerjs js -->
<script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
<script>
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();
</script>

<script>
    $(document).ready(function() {

        $('#invoice_number').hide();
        $('#Section_name').hide();
        $('#product_name').hide();
        $('#start_at_Section').hide();
        $('#end_at_Section').hide();
        $('#btn_s').hide();



        $('input[type="radio"]').click(function() {
            if ($(this).attr('id') == 'type_div') {
                $('#invoice_number').hide();
                $('#Section_name').hide();
                $('#product_name').hide();
                $('#start_at_Section').hide();
                $('#end_at_Section').hide();
                $('#btn_s').hide();


                $('#btn_in').show();
                $('#type').show();
                $('#start_at').show();
                $('#end_at').show();
            } else if ($(this).attr('id') == 'radio_2') {
                $('#invoice_number').hide();


                $('#Section_name').show();
                $('#product_name').show();
                $('#start_at_Section').show();
                $('#end_at_Section').show();
                $('#btn_s').show();




                $('#btn_in').hide();
                $('#type').hide();
                $('#start_at').hide();
                $('#end_at').hide();
            } else {
                $('#invoice_number').show();


                $('#Section_name').hide();
                $('#product_name').hide();
                $('#start_at_Section').hide();
                $('#end_at_Section').hide();
                $('#btn_s').hide();





                $('#btn_in').show();

                $('#type').hide();
                $('#start_at').hide();
                $('#end_at').hide();
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('select[name="Section"]').on('change', function() {
            var SectionId = $(this).val();
            if (SectionId) {
                $.ajax({
                    url: "{{ URL::to('section') }}/" + SectionId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="product"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="product"]').append('<option value="' +
                                value + '">' + value + '</option>');
                        });
                    },
                });

            } else {
                console.log('AJAX load did not work');
            }
        });

    });
</script>


@endsection
