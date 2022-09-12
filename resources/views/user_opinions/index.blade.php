@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection
@section('content-header')
    <h1>User Opinions</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Opinions</a></li>
        <li class="active">Listing</li>
    </ol>
@endsection

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-xs-12">

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Filter Box</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-sm btn-default" data-widget="collapse">
                            <i class="fa fa-compress"></i>
                        </button>
                        <a href="{{ route('opinions.index') }}" class="btn btn-info btn-sm" title="Collapse">
                            <i class="fa fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>

                <div class="box-body">

                    <form id="formSearch" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label for="mobile_id" class="control-label col-lg-2">Mobile Id</label>
                            <div class="col-lg-4">
                                <input class="form-control" name="mobile_id" id="mobile_id">
                            </div>

                            <label for="status" class="control-label col-lg-2">Status</label>
                            <div class="col-lg-4">
                                <select class="form-control" name="status" id="status">
                                    <option value="">------All Opinions------</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Pending" selected>Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="date_range" class="control-label col-lg-2">Date</label>
                            <div class="col-lg-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control date-range-picker" name="date_range" type="text"
                                           id="date_range">
                                </div>
                            </div>

                            <div class="col-lg-1 pull-right">
                                <button type="button" id="btnSearch" class="btn btn-primary pull-right">Go
                                </button>
                            </div>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">

                    <div class="table-responsive hide-quick-search">
                        <table id="dataTable" class="table table-bordered table-striped" style="width: 100%;">
                            <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Mobile Id</th>
                                <th>User Id</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th style="width:100px;" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

<!-- page script -->
@section('javascript')
<script src="{{ asset('moment/min/moment.min.js') }}"></script>
<script src="{{ asset('bootstrap-daterangepicker/daterangepicker-custom.js') }}"></script>
    
<script>

    $(function () {

        let start = moment().subtract(29, 'days');
        let end = moment();
        let startDate = start.format('YYYY-MM-DD');
        let endDate = end.format('YYYY-MM-DD');
        let dateRangeBtnObj = $('#date_range');
        let dataTableUrl = '';


        let dataTable = $('#dataTable').DataTable({
            "order": [[5, "desc"]],
            processing: false,
            serverSide: true,
            ajax: function (data, callback, settings) {
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'mobile_id', name: 'mobile_id'},
                {data: 'user_id', name: 'user_id'},
                {data: 'rating', name: 'rating'},
                {data: 'status', name: 'status'},
                {data: 'created_at', name: 'created_at'},
                {
                    data: 'action',
                    name: 'action',
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
            ],
            "pageLength": 10,
            "pagination": true,
            "columnDefs": [
                {"orderable": false, "targets": -1},
                {"orderable": false, "targets": 0}
            ],
            initComplete: function () {
                $('.dataTables_filter').append(
                );
            },
            drawCallback: function () {
                $('.dataTables_filter label:first').show();

            }
        });

        dateRangeBtnObj.daterangepicker({
            opens: 'right',
            showDropdowns: true,
            linkedCalendars: false,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'All Time': [moment('1970-01-01'), moment()],
            },
            locale: {
                format: dateFormat
            },
            startDate: start,
            endDate: end,
        });

        // Filter the datatable on the datepicker apply event
        dateRangeBtnObj.on('apply.daterangepicker', function (ev, picker) {
            startDate = picker.startDate.format('YYYY-MM-DD');
            endDate = picker.endDate.format('YYYY-MM-DD');
        });

        $('#btnSearch').click(function () {
            ajaxRequest();
        });

        let getUrlQueries = function () {
            let mobileId = $('#mobile_id').val();
            let status = $('#status').val();

            return '?startDate=' + startDate
                + '&endDate=' + endDate
                + '&mobileId=' + mobileId
                + '&status=' + status;
        };

        let ajaxRequest = function () {
            dataTableUrl = getUrlQueries();
            dataTable.ajax.url('{{ route('opinions.index') }}' + dataTableUrl);
            dataTable.draw();
        };

        ajaxRequest();
    });
</script>
@endsection
