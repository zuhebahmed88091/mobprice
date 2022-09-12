@extends('layouts.app')

@section('content-header')
    <h1>News</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> News</a></li>
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

                    @if(count($allNews) == 0)
                    <div class="row">
                        <div class="col-sm-12">
                            @if (App\Helpers\CommonHelper::isCapable('news.create'))
                            <a href="{{ route('news.create') }}"
                               class="btn btn-sm btn-success pull-right"
                               title="Create New Mobile Ram">
                                <i aria-hidden="true" class="fa fa-plus"></i> Create
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="panel-body text-center">
                        <h4>No News Available!</h4>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
								<th>SL</th>
								<th>Thamble </th>
								<th>Title</th>
                                <th>Status</th>
								<th>Created At</th>
								<th style="min-width:100px;" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php ($serial = 1)
                            @foreach($allNews as $news)
                            <tr>
								<td>{{ $serial++ }}</td>
								<td class="text-center">
                                    <img src="{{ asset('storage/news/' . optional($news)->image) }}"
                                         alt="Profile Image"
                                         style="width: 24px; height: 24px">
                                </td>
								<td>{{ $news->title }}</td>
								<td>{{ $news->status }}</td>
                                <td>{{ $news->created_at }}</td>
								<td class="text-center" style="min-width:100px;">

                                    <form method="POST"
                                          action="{!! route('news.destroy', $news->id) !!}"
                                          accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        @if (App\Helpers\CommonHelper::isCapable('news.show'))
                                        <a href="{{ route('news.show', $news->id ) }}"
                                           class="btn btn-xs btn-info" title="Show News">
                                            <i aria-hidden="true" class="fa fa-eye"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('news.edit'))
                                        <a href="{{ route('news.edit', $news->id ) }}"
                                           class="btn btn-xs btn-primary" title="Edit News">
                                            <i aria-hidden="true" class="fa fa-pencil"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('news.destroy'))
                                        <button type="submit" class="btn btn-xs btn-danger"
                                                title="Delete News"
                                                onclick="return confirm('Delete News?')">
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
                // "order": [[4, "desc"]],
                "columnDefs": [
                    {"orderable": false, "targets": -1}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('news.create'))' +
                        '<a href="{{ route('news.create') }}" ' +
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
