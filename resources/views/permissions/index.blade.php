@extends('layouts.app')

@section('content-header')
    <h1>Permissions</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Permissions</a></li>
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
                <a href="{{ route('permissions.index') }}" class="btn btn-info btn-sm" title="Collapse">
                    <i class="fa fa-refresh"></i> Reset
                </a>
                @if (App\Helpers\CommonHelper::isCapable('permissions.create'))
                    <a href="{{ route('permissions.create') }}"
                       class="btn btn-sm btn-success"
                       title="Add New Medicine">
                        <i class="fa fa-plus"></i> Create
                    </a>
                @endif
            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-xs-12">

                    <form id="formSearch" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label for="module_id" class="control-label col-lg-2">Module</label>
                            <div class="col-lg-4">
                                <select class="form-control select-admin-lte" name="module_id" id="module_id">
                                    <option value="">------ All ------</option>
                                    @foreach ($modules as $key => $title)
                                        <option value="{{ $key }}">
                                            {{ $title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <label for="name" class="control-label col-lg-1">Name</label>
                            <div class="col-lg-4">
                                <input class="form-control" name="name" type="text" id="name">
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
                                <th>Module</th>
                                <th>Name</th>
                                <th>Display Name</th>
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
            let dataTableUrl = '';
            let dataTable = $('#dataTable').DataTable({
                "order": [[4, "desc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'module_id', name: 'module_id'},
                    {data: 'name', name: 'name'},
                    {data: 'display_name', name: 'display_name'},
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
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('permissions.exportXLSX'))' +
                        `{!! view('commons.button') !!}` +
                        '@endif'
                    );

                    $('#btnExportXLSX').click(function () {
                        location.href = '{{ route('permissions.exportXLSX') }}' + dataTableUrl;
                    });
                }
            });

            let getUrlQueries = function() {
                let moduleId = $('#module_id').val();
                let name = $('#name').val();
                return '?moduleId=' + moduleId + '&name=' + name;
            };

            let ajaxRequest = function () {
                dataTableUrl = getUrlQueries();
                dataTable.ajax.url('{{ route('permissions.index') }}' + dataTableUrl);
                dataTable.draw();
            };

            ajaxRequest();

            $('#btnSearch').click(function () {
                ajaxRequest();
            });
        });
    </script>
@endsection
