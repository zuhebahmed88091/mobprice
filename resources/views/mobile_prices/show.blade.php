@extends('layouts.app')

@section('content-header')
    <h1>Mobile Price Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('mobile_prices.index') }}">
                <i class="fa fa-dashboard"></i> Mobile Prices
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Mobile Price' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('mobile_prices.destroy', $mobilePrice->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('mobile_prices.index') }}" class="btn btn-sm btn-info" title="Show All Mobile Price">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('mobile_prices.create') }}" class="btn btn-sm btn-success" title="Create New Mobile Price">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('mobile_prices.edit', $mobilePrice->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit Mobile Price">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete Mobile Price"
                            onclick="return confirm('Delete Mobile Price?')">
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
                        <th>Mobile</th>
                        <td>{{ optional($mobilePrice->mobile)->title }}</td>
                    </tr>
                    <tr>
                        <th>Region</th>
                        <td>{{ optional($mobilePrice->region)->title }}</td>
                    </tr>
                    <tr>
                        <th>Mobile Ram</th>
                        <td>{{ optional($mobilePrice->mobileRam)->title }}</td>
                    </tr>
                    <tr>
                        <th>Mobile Storage</th>
                        <td>{{ optional($mobilePrice->mobileStorage)->title }}</td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>{{ $mobilePrice->price }}</td>
                    </tr>
                    <tr>
                        <th>Usd Price</th>
                        <td>{{ $mobilePrice->usd_price }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $mobilePrice->status }}</td>
                    </tr>
                    <tr>
                        <th>Affiliate Url</th>
                        <td>{{ $mobilePrice->affiliate_url }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $mobilePrice->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $mobilePrice->updated_at }}</td>
                    </tr>

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
