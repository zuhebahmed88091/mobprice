@extends('layouts.app')

@section('content-header')
    <h1>Variation Price Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('variation_prices.index') }}">
                <i class="fa fa-dashboard"></i> Variation Prices
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Variation Price' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('variation_prices.destroy', $variationPrice->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('variation_prices.index') }}" class="btn btn-sm btn-info" title="Show All Variation Price">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('variation_prices.create') }}" class="btn btn-sm btn-success" title="Create New Variation Price">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('variation_prices.edit', $variationPrice->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit Variation Price">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete Variation Price"
                            onclick="return confirm('Delete Variation Price?')">
                        <i aria-hidden="true" class="fa fa-trash"></i>
                    </button>

                </form>

            </div>

        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-show">
                <tbody>
                    <tr>
                        <th>Region</th>
                        <td>{{ optional($variationPrice->region)->title }}</td>
                    </tr>
                    <tr>
                        <th>Ram</th>
                        <td>{{ optional($variationPrice->ram)->title }}</td>
                    </tr>
                    <tr>
                        <th>Storage</th>
                        <td>{{ optional($variationPrice->storage)->title }}</td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>{{ $variationPrice->price }}</td>
                    </tr>
                    <tr>
                        <th>Usd Price</th>
                        <td>{{ $variationPrice->usd_price }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $variationPrice->status }}</td>
                    </tr>
                    <tr>
                        <th>Affiliate Url</th>
                        <td>{{ $variationPrice->affiliate_url }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $variationPrice->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $variationPrice->updated_at }}</td>
                    </tr>

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
