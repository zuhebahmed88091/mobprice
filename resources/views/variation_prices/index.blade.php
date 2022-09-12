@extends('layouts.app')

@section('content-header')
    <h1>Variation Prices</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Variation Prices</a></li>
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

                    @if(count($variationPrices) == 0)
                    <div class="row">
                        <div class="col-sm-12">
                            @if (App\Helpers\CommonHelper::isCapable('variation_prices.create'))
                            <a href="{{ route('variation_prices.create') }}"
                               class="btn btn-sm btn-success pull-right"
                               title="Create New Variation Price">
                                <i aria-hidden="true" class="fa fa-plus"></i> Create
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="panel-body text-center">
                        <h4>No Variation Prices Available!</h4>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
								<th>Id</th>
								<th>Region</th>
								<th>Ram</th>
								<th>Storage</th>
								<th class="text-right">Price</th>
								<th class="text-right">Usd Price</th>
								<th>Status</th>
								<th style="min-width:100px;" class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($variationPrices as $variationPrice)
                            <tr>
								<td>{{ $variationPrice->id }}</td>
								<td>{{ optional($variationPrice->region)->title }}</td>
								<td>{{ optional($variationPrice->ram)->title }}</td>
								<td>{{ optional($variationPrice->storage)->title }}</td>
								<td class="text-right">{{ $variationPrice->price }}</td>
								<td class="text-right">{{ $variationPrice->usd_price }}</td>
								<td>{{ $variationPrice->status }}</td>
								<td class="text-center" style="min-width:100px;">

                                    <form method="POST"
                                          action="{!! route('variation_prices.destroy', $variationPrice->id) !!}"
                                          accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        @if (App\Helpers\CommonHelper::isCapable('variation_prices.show'))
                                        <a href="{{ route('variation_prices.show', $variationPrice->id ) }}"
                                           class="btn btn-xs btn-info" title="Show Variation Price">
                                            <i aria-hidden="true" class="fa fa-eye"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('variation_prices.edit'))
                                        <a href="{{ route('variation_prices.edit', $variationPrice->id ) }}"
                                           class="btn btn-xs btn-primary" title="Edit Variation Price">
                                            <i aria-hidden="true" class="fa fa-pencil"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('variation_prices.destroy'))
                                        <button type="submit" class="btn btn-xs btn-danger"
                                                title="Delete Variation Price"
                                                onclick="return confirm('Delete Variation Price?')">
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
                        '@if (App\Helpers\CommonHelper::isCapable('variation_prices.create'))' +
                        '<a href="{{ route('variation_prices.create') }}" ' +
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
