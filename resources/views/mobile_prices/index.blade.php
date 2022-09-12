@extends('layouts.app')

@section('content-header')
    <h1>Mobile Prices</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Mobile Prices</a></li>
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

                    @if(count($mobilePrices) == 0)
                    <div class="row">
                        <div class="col-sm-12">
                            @if (App\Helpers\CommonHelper::isCapable('mobile_prices.create'))
                            <a href="{{ route('mobile_prices.create') }}"
                               class="btn btn-sm btn-success pull-right"
                               title="Create New Mobile Price">
                                <i aria-hidden="true" class="fa fa-plus"></i> Create
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="panel-body text-center">
                        <h4>No Mobile Prices Available!</h4>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
								<th>Id</th>
								<th>Mobile</th>
								<th>Region</th>
								<th>Mobile Ram</th>
								<th>Mobile Storage</th>
								<th class="text-right">Price</th>
								<th class="text-right">Usd Price</th>
								<th>Status</th>
								<th style="min-width:100px;" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($mobilePrices as $mobilePrice)
                            <tr>
								<td>{{ $mobilePrice->id }}</td>
								<td>{{ optional($mobilePrice->mobile)->title }}</td>
								<td>{{ optional($mobilePrice->region)->title }}</td>
								<td>{{ optional($mobilePrice->mobileRam)->title }}</td>
								<td>{{ optional($mobilePrice->mobileStorage)->title }}</td>
								<td class="text-right">{{ $mobilePrice->price }}</td>
								<td class="text-right">{{ $mobilePrice->usd_price }}</td>
								<td>{{ $mobilePrice->status }}</td>
								<td class="text-center" style="min-width:100px;">

                                    <form method="POST"
                                          action="{!! route('mobile_prices.destroy', $mobilePrice->id) !!}"
                                          accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        @if (App\Helpers\CommonHelper::isCapable('mobile_prices.show'))
                                        <a href="{{ route('mobile_prices.show', $mobilePrice->id ) }}"
                                           class="btn btn-xs btn-info" title="Show Mobile Price">
                                            <i aria-hidden="true" class="fa fa-eye"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('mobile_prices.edit'))
                                        <a href="{{ route('mobile_prices.edit', $mobilePrice->id ) }}"
                                           class="btn btn-xs btn-primary" title="Edit Mobile Price">
                                            <i aria-hidden="true" class="fa fa-pencil"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('mobile_prices.destroy'))
                                        <button type="submit" class="btn btn-xs btn-danger"
                                                title="Delete Mobile Price"
                                                onclick="return confirm('Delete Mobile Price?')">
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
                "columnDefs": [
                    {"orderable": false, "targets": -1}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('mobile_prices.create'))' +
                        '<a href="{{ route('mobile_prices.create') }}" ' +
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
