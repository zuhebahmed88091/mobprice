@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
    <h1>Event Logs</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Event Logs</a></li>
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

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Filter Box</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-sm btn-default" data-widget="collapse">
                    <i class="fa fa-compress"></i>
                </button>
                <a href="{{ route('event_logs.index') }}" class="btn btn-info btn-sm" title="Collapse">
                    <i class="fa fa-refresh"></i> Reset
                </a>
            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-xs-12">

                    <form id="formSearch" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label for="user_id" class="control-label col-lg-2">User</label>
                            <div class="col-lg-4">
                                <select class="form-control select-admin-lte" name="user_id" id="user_id">
                                    <option value="">------ All ------</option>
                                    @foreach ($users as $key => $title)
                                        <option value="{{ $key }}">
                                            {{ $title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <label for="created_at" class="control-label col-lg-2">Created At</label>
                            <div class="col-lg-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control date-range-picker" name="created_at"
                                           type="text"
                                           id="created_at">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="changes" class="control-label col-lg-2">Changes</label>
                            <div class="col-lg-4">
                                <input class="form-control" name="changes" id="changes">
                            </div>

                            <label for="end_point" class="control-label col-lg-2">End Point</label>
                            <div class="col-lg-3">
                                <input class="form-control date-range-picker" name="end_point" id="end_point">
                            </div>

                            <div class="col-lg-1 pull-right">
                                <button type="button" id="btnSearch" class="btn btn-primary pull-right">
                                    Go
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
                                <th>User</th>
                                <th>End Point</th>
                                <th>Changes</th>
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
    <script>
        $(function () {

            let start = moment().subtract(29, 'days');
            let end = moment();
            let startDate = start.format('YYYY-MM-DD');
            let endDate = end.format('YYYY-MM-DD');
            let dateRangeObj = $('#created_at');
            let dataTableUrl = '';

            let dataTable = $('#dataTable').DataTable({
                "order": [[4, "desc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'user', name: 'user'},
                    {data: 'end_point', name: 'end_point'},
                    {data: 'changes', name: 'changes'},
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
                    {"searchable": false, "orderable": false, "targets": 0}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(`{!! view('commons.button') !!}`);

                    $('#btnExportXLSX').click(function () {
                        location.href = '{{ route('event_logs.exportXLSX') }}' + dataTableUrl;
                    });
                }
            });

            let dateRangeOptions = {
                opens: 'left',
                showDropdowns: true,
                linkedCalendars: false,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    format: dateFormat
                },
                startDate: start,
                endDate: end,
            };

            // daterangepicker for purchase date
            dateRangeOptions.opens = 'left';
            dateRangeObj.daterangepicker(dateRangeOptions);
            dateRangeObj.on('apply.daterangepicker', function (ev, picker) {
                startDate = picker.startDate.format('YYYY-MM-DD');
                endDate = picker.endDate.format('YYYY-MM-DD');
            });

            let getUrlQueries = function () {
                let userId = $('#user_id').val();
                let changes = $('#changes').val();
                let endPoint = $('#end_point').val();
                return '?startDate=' + startDate
                    + '&endDate=' + endDate
                    + '&userId=' + userId
                    + '&changes=' + changes
                    + '&endPoint=' + endPoint;
            };

            let ajaxRequest = function () {
                dataTableUrl = getUrlQueries();
                dataTable.ajax.url('{{ route('event_logs.index') }}' + dataTableUrl);
                dataTable.draw();
            };

            ajaxRequest();

            $('#btnSearch').click(function () {
                ajaxRequest();
            });
        });
    </script>
@endsection
