@extends('layouts.app')

@section('content-header')
    <h1>Filter Options</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Filter Options</a></li>
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

                    @if(count($filterOptions) == 0)
                    <div class="row">
                        <div class="col-sm-12">
                            @if (App\Helpers\CommonHelper::isCapable('filter_options.create'))
                            <a href="{{ route('filter_options.create') }}"
                               class="btn btn-sm btn-success pull-right"
                               title="Create New Filter Option">
                                <i aria-hidden="true" class="fa fa-plus"></i> Create
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="panel-body text-center">
                        <h4>No Filter Options Available!</h4>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
								<th>SL.</th>
								<th>Filter Section</th>
								<th>Name</th>
								<th>Value</th>
								<th>Status</th>
								<th class="text-right">Sorting</th>
								<th style="min-width:100px;" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($filterOptions as $filterOption)
                            <tr>
								<td>{{ $serial++ }}</td>
								<td>{{ optional($filterOption->filterSection)->label }}</td>
								<td>{{ $filterOption->name }}</td>
								<td>{{ $filterOption->value }}</td>
								<td>{{ $filterOption->status }}</td>
								<td class="text-right">{{ $filterOption->sorting }}</td>
								<td class="text-center" style="min-width:100px;">

                                    <form method="POST"
                                          action="{!! route('filter_options.destroy', $filterOption->id) !!}"
                                          accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        @if (App\Helpers\CommonHelper::isCapable('filter_options.show'))
                                        <a href="{{ route('filter_options.show', $filterOption->id ) }}"
                                           class="btn btn-xs btn-info" title="Show Filter Option">
                                            <i aria-hidden="true" class="fa fa-eye"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('filter_options.edit'))
                                        <a href="{{ route('filter_options.edit', $filterOption->id ) }}"
                                           class="btn btn-xs btn-primary" title="Edit Filter Option">
                                            <i aria-hidden="true" class="fa fa-pencil"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('filter_options.destroy'))
                                        <button type="submit" class="btn btn-xs btn-danger"
                                                title="Delete Filter Option"
                                                onclick="return confirm('Delete Filter Option?')">
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
                "pageLength": 50,
                "ordering": false,
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('filter_options.create'))' +
                        '<a href="{{ route('filter_options.create') }}" ' +
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
