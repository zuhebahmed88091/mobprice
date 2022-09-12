@extends('layouts.app')

@section('content-header')
    <h1>Testimonials</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Testimonials</a></li>
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

                    @if(count($testimonials) == 0)
                    <div class="row">
                        <div class="col-sm-12">
                            @if (App\Helpers\CommonHelper::isCapable('testimonials.create'))
                            <a href="{{ route('testimonials.create') }}"
                               class="btn btn-sm btn-success pull-right"
                               title="Create New Testimonial">
                                <i aria-hidden="true" class="fa fa-plus"></i> Create
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="panel-body text-center">
                        <h4>No Testimonials Available!</h4>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
								<th>SL.</th>
								<th>Customer</th>
								<th class="text-right">Rating</th>
								<th>Status</th>
								<th>Created At</th>
								<th style="min-width:100px;" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($testimonials as $testimonial)
                            <tr>
								<td></td>
								<td>{{ optional($testimonial->customer)->name }}</td>
								<td class="text-right">{{ $testimonial->rating }}</td>
								<td>{{ $testimonial->status }}</td>
								<td>{{ $testimonial->created_at }}</td>
								<td class="text-center" style="min-width:100px;">

                                    <form method="POST"
                                          action="{!! route('testimonials.destroy', $testimonial->id) !!}"
                                          accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        @if (App\Helpers\CommonHelper::isCapable('testimonials.show'))
                                        <a href="{{ route('testimonials.show', $testimonial->id ) }}"
                                           class="btn btn-xs btn-info" title="Show Testimonial">
                                            <i aria-hidden="true" class="fa fa-eye"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('testimonials.edit'))
                                        <a href="{{ route('testimonials.edit', $testimonial->id ) }}"
                                           class="btn btn-xs btn-primary" title="Edit Testimonial">
                                            <i aria-hidden="true" class="fa fa-pencil"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('testimonials.destroy'))
                                        <button type="submit" class="btn btn-xs btn-danger"
                                                title="Delete Testimonial"
                                                onclick="return confirm('Delete Testimonial?')">
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
            </div>
        </div>
    </div>

@endsection

<!-- page script -->
@section('javascript')
    <script>
        $(function () {
            let dataTable = $('#dataTable').DataTable({
                "order": [[4, "asc"]],
                "columnDefs": [
                    {"orderable": false, "targets": -1},
                    {"searchable": false, "orderable": false, "targets": 0}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('testimonials.exportXLSX'))' +
                        `{!! view('commons.button') !!}` +
                        '@endif' +

                        '@if (App\Helpers\CommonHelper::isCapable('testimonials.create'))' +
                        '<a href="{{ route('testimonials.create') }}" ' +
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
                location.href = '{{ route('testimonials.exportXLSX') }}';
            });
        });
    </script>
@endsection
