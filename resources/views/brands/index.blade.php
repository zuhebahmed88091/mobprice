@extends('layouts.app')

@section('content-header')
    <h1>Brands</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Brands</a></li>
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

                    @if(count($brands) == 0)
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="{{ route('brands.create') }}"
                                   class="btn btn-sm btn-success pull-right"
                                   title="Create New Brand">
                                    <i aria-hidden="true" class="fa fa-plus"></i> Create
                                </a>
                            </div>
                        </div>

                        <div class="panel-body text-center">
                            <h4>No Brands Available!</h4>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th class="text-start">Logo</th>
                                    <th>Title</th>
                                    <th>Total Item</th>
                                    <th>Sorting</th>
                                    <th>Revision</th>
                                    <th style="width:100px;" class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($brands as $brand)
                                    <tr>
                                        <td>{{ $brand->id }}</td>
                                        <td class="text-start"><img src="{{ asset('storage/brands/' . optional($brand)->image) }}"
                                            alt="Brand Logo"
                                            style=" height: 25px">
                                        </td>
                                        <td>{{ $brand->title }}</td>
                                        <td>{{ $brand->total_item }}</td>
                                        <td>{{ $brand->sorting }}</td>
                                        <td>{{ $brand->revision }}</td>

                                        <td class="text-center" style="width:100px;">

                                            <form method="POST"
                                                  action="{!! route('brands.destroy', $brand->id) !!}"
                                                  accept-charset="UTF-8">
                                                <input name="_method" value="DELETE" type="hidden">
                                                {{ csrf_field() }}

                                                <a href="{{ route('brands.show', $brand->id ) }}"
                                                   class="btn btn-xs btn-info" title="Show Brand">
                                                    <i aria-hidden="true" class="fa fa-eye"></i>
                                                </a>

                                                <a href="{{ route('brands.edit', $brand->id ) }}"
                                                   class="btn btn-xs btn-primary" title="Edit Brand">
                                                    <i aria-hidden="true" class="fa fa-pencil"></i>
                                                </a>

                                                <button type="submit" class="btn btn-xs btn-danger"
                                                        title="Delete Brand"
                                                        onclick="return confirm('Delete Brand?')">
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
                "order": [[4, "desc"]],
                "columnDefs": [
                    {"orderable": false, "targets": -1}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '<a href="{{ route('brands.create') }}" ' +
                        'style="margin-left: 10px" ' +
                        'class="btn btn-sm btn-success" ' +
                        'title="Create New Brand"> ' +
                        '<i aria-hidden="true" class="fa fa-plus"></i> Create' +
                        '</a>'
                    );
                }
            });
        });
    </script>
@endsection
