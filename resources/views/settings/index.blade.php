@extends('layouts.app')

@section('content-header')
    <h1>Settings</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Settings</a></li>
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

                    @if(count($settings) == 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <a href="{{ route('settings.create') }}"
                               class="btn btn-sm btn-success pull-right"
                               title="Create New Setting">
                                <i aria-hidden="true" class="fa fa-plus"></i> Create
                            </a>
                        </div>
                    </div>

                    <div class="panel-body text-center">
                        <h4>No Settings Available!</h4>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
								<th>Id</th>
								<th>Title</th>
								<th>Constant</th>
								<th>Value</th>
								<th>Field Type</th>
								<th>Options</th>
								<th>Status</th>
								<th style="width:100px;" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($settings as $setting)
                            <tr>
								<td>{{ $setting->id }}</td>
								<td>{{ $setting->title }}</td>
								<td>{{ $setting->constant }}</td>
								<td>{{ $setting->value }}</td>
								<td>{{ $setting->field_type }}</td>
								<td>{{ $setting->options }}</td>
								<td>{{ $setting->status }}</td>
								<td class="text-center" style="width:100px;">

                                    <form method="POST"
                                          action="{!! route('settings.destroy', $setting->id) !!}"
                                          accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        <a href="{{ route('settings.show', $setting->id ) }}"
                                           class="btn btn-xs btn-info" title="Show Setting">
                                            <i aria-hidden="true" class="fa fa-eye"></i>
                                        </a>

                                        <a href="{{ route('settings.edit', $setting->id ) }}"
                                           class="btn btn-xs btn-primary" title="Edit Setting">
                                            <i aria-hidden="true" class="fa fa-pencil"></i>
                                        </a>

                                        <button type="submit" class="btn btn-xs btn-danger"
                                                title="Delete Setting"
                                                onclick="return confirm('Delete Setting?')">
                                            <i aria-hidden="true" class="fa fa-trash"></i>
                                        </button>
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
            $('#dataTable').DataTable({
                "columnDefs": [
                    {"orderable": false, "targets": -1}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '<a href="{{ route('settings.create') }}" ' +
                        'style="margin-left: 10px" ' +
                        'class="btn btn-sm btn-success" ' +
                        'title="Create New User"> ' +
                        '<i aria-hidden="true" class="fa fa-plus"></i> Create' +
                        '</a>'
                    );
                }
            })
        });
    </script>
@endsection
