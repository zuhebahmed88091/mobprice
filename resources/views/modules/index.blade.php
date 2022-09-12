@extends('layouts.app')

@section('content-header')
    <h1>Modules</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Modules</a></li>
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
            <div class="box">
                <div class="box-body">

                    @if(count($modules) == 0)
                    <div class="row">
                        <div class="col-sm-12">
                            @if (App\Helpers\CommonHelper::isCapable('modules.create'))
                            <a href="{{ route('modules.create') }}"
                               class="btn btn-sm btn-success pull-right"
                               title="Create New Module">
                                <i aria-hidden="true" class="fa fa-plus"></i> Create
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="panel-body text-center">
                        <h4>No Modules Available!</h4>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
								<th>SL.</th>
								<th>Name</th>
								<th>Slug</th>
								<th>Fa Icon</th>
								<th>Status</th>
								<th class="text-right">Sorting</th>
								<th style="min-width:100px;" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($modules as $module)
                            <tr>
								<td></td>
								<td>{{ $module->name }}</td>
								<td>{{ $module->slug }}</td>
								<td>{{ $module->fa_icon }}</td>
								<td>{{ $module->status }}</td>
								<td class="text-right">{{ $module->sorting }}</td>
								<td class="text-center" style="min-width:100px;">

                                    <form method="POST"
                                          action="{!! route('modules.destroy', $module->id) !!}"
                                          accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        @if (App\Helpers\CommonHelper::isCapable('modules.show'))
                                        <a href="{{ route('modules.show', $module->id ) }}"
                                           class="btn btn-xs btn-info" title="Show Module">
                                            <i aria-hidden="true" class="fa fa-eye"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('modules.edit'))
                                        <a href="{{ route('modules.edit', $module->id ) }}"
                                           class="btn btn-xs btn-primary" title="Edit Module">
                                            <i aria-hidden="true" class="fa fa-pencil"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('modules.destroy'))
                                        <button type="submit" class="btn btn-xs btn-danger"
                                                title="Delete Module"
                                                onclick="return confirm('Delete Module?')">
                                            <i aria-hidden="true" class="fa fa-trash"></i>
                                        </button>
                                        @endif
                                    </form>

                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    @endif

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

<!-- page script -->
@section('javascript')
    <script>
        $(function () {
            let dataTable = $('#dataTable').DataTable({
                "order": [[5, "asc"]],
                "columnDefs": [
                    {"orderable": false, "targets": -1},
                    {"searchable": false, "orderable": false, "targets": 0}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('modules.exportXLSX'))' +
                        `{!! view('commons.button') !!}` +
                        '@endif' +

                        '@if (App\Helpers\CommonHelper::isCapable('modules.create'))' +
                        '<a href="{{ route('modules.create') }}" ' +
                        'class="btn btn-sm btn-flat btn-success" ' +
                        'title="Create New User"> ' +
                        '<i aria-hidden="true" class="fa fa-plus"></i> Create' +
                        '</a>' +
                        '@endif'
                    );
                }
            });

            dataTable.on('order.dt search.dt', function () {
                dataTable.column(0, {search: 'applied', order: 'applied'})
                    .nodes()
                    .each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();

            $('#btnExportXLSX').click(function () {
                location.href = '{{ route('modules.exportXLSX') }}';
            });
        });
    </script>
@endsection
