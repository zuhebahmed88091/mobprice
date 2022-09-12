@extends('layouts.app')

@section('content-header')
    <h1>Roles</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Roles</a></li>
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

                    @if(count($roles) == 0)
                        <div class="row">
                            <div class="col-sm-12">
                                @if (App\Helpers\CommonHelper::isCapable('roles.create'))
                                    <a href="{{ route('roles.create') }}"
                                       class="btn btn-sm btn-success pull-right"
                                       title="Create New Role">
                                        <i aria-hidden="true" class="fa fa-plus"></i> Create
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="panel-body text-center">
                            <h4>No Roles Available!</h4>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>SL.</th>
                                    <th>Name</th>
                                    <th>Display Name</th>
                                    <th>Created At</th>
                                    <th style="width:100px;" class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td></td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->display_name }}</td>
                                        <td>{{ $role->created_at }}</td>

                                        <td class="text-center" style="width:120px;">

                                            <form method="POST"
                                                  action="{!! route('roles.destroy', $role->id) !!}"
                                                  accept-charset="UTF-8">
                                                <input name="_method" value="DELETE" type="hidden">
                                                {{ csrf_field() }}

                                                @if (App\Helpers\CommonHelper::isCapable('roles.module-permissions'))
                                                    <a href="{{ route('roles.module-permissions', $role->id ) }}"
                                                       class="btn btn-xs btn-warning" title="Assign Permissions">
                                                        <i aria-hidden="true" class="fa fa-key"></i>
                                                    </a>
                                                @endif

                                                @if (App\Helpers\CommonHelper::isCapable('roles.show'))
                                                    <a href="{{ route('roles.show', $role->id ) }}"
                                                       class="btn btn-xs btn-info" title="Show Role">
                                                        <i aria-hidden="true" class="fa fa-eye"></i>
                                                    </a>
                                                @endif

                                                @if (App\Helpers\CommonHelper::isCapable('roles.edit'))
                                                    <a href="{{ route('roles.edit', $role->id ) }}"
                                                       class="btn btn-xs btn-primary" title="Edit Role">
                                                        <i aria-hidden="true" class="fa fa-pencil"></i>
                                                    </a>
                                                @endif

                                                @if (App\Helpers\CommonHelper::isCapable('roles.destroy'))
                                                    <button type="submit" class="btn btn-xs btn-danger"
                                                            title="Delete Role"
                                                            onclick="return confirm('Delete Role?')">
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
                "order": [[3, "desc"]],
                "columnDefs": [
                    {"orderable": false, "targets": -1},
                    {"searchable": false, "orderable": false, "targets": 0}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('roles.exportXLSX'))' +
                        `{!! view('commons.button') !!}` +
                        '@endif' +

                        '@if (App\Helpers\CommonHelper::isCapable('roles.create'))' +
                        '<a href="{{ route('roles.create') }}" ' +
                        'class="btn btn-sm btn-flat btn-success" ' +
                        'title="Create New Role"> ' +
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
                location.href = '{{ route('roles.exportXLSX') }}';
            });
        });
    </script>
@endsection
