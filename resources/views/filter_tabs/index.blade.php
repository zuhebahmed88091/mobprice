@extends('layouts.app')

@section('content-header')
    <h1>Filter Tabs</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Filter Tabs</a></li>
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

                    @if(count($filterTabs) == 0)
                    <div class="row">
                        <div class="col-sm-12">
                            @if (App\Helpers\CommonHelper::isCapable('filter_tabs.create'))
                            <a href="{{ route('filter_tabs.create') }}"
                               class="btn btn-sm btn-success pull-right"
                               title="Create New Filter Tab">
                                <i aria-hidden="true" class="fa fa-plus"></i> Create
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="panel-body text-center">
                        <h4>No Filter Tabs Available!</h4>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
								<th>Id</th>
								<th>Title</th>
								<th class="text-right">Sorting</th>
								<th>Status</th>
								<th style="min-width:100px;" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($filterTabs as $filterTab)
                            <tr>
								<td>{{ $filterTab->id }}</td>
								<td>{{ $filterTab->title }}</td>
								<td class="text-right">{{ $filterTab->sorting }}</td>
								<td>{{ $filterTab->status }}</td>
								<td class="text-center" style="min-width:100px;">

                                    <form method="POST"
                                          action="{!! route('filter_tabs.destroy', $filterTab->id) !!}"
                                          accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        @if (App\Helpers\CommonHelper::isCapable('filter_tabs.show'))
                                        <a href="{{ route('filter_tabs.show', $filterTab->id ) }}"
                                           class="btn btn-xs btn-info" title="Show Filter Tab">
                                            <i aria-hidden="true" class="fa fa-eye"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('filter_tabs.edit'))
                                        <a href="{{ route('filter_tabs.edit', $filterTab->id ) }}"
                                           class="btn btn-xs btn-primary" title="Edit Filter Tab">
                                            <i aria-hidden="true" class="fa fa-pencil"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('filter_tabs.destroy'))
                                        <button type="submit" class="btn btn-xs btn-danger"
                                                title="Delete Filter Tab"
                                                onclick="return confirm('Delete Filter Tab?')">
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
            $('#dataTable').DataTable({
                "order": [[2, "asc"]],
                "pageLength": 50,
                "columnDefs": [
                    {"orderable": false, "targets": -1}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('filter_tabs.create'))' +
                        '<a href="{{ route('filter_tabs.create') }}" ' +
                        'style="margin-left: 10px" ' +
                        'class="btn btn-sm btn-success" ' +
                        'title="Create New User"> ' +
                        '<i aria-hidden="true" class="fa fa-plus"></i> Create' +
                        '</a>' +
                        '@endif'
                    );
                }
            })
        });
    </script>
@endsection
