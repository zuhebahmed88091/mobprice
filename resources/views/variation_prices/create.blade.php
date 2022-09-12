@extends('layouts.app')

@section('content-header')
    <h1>Create Variation Price</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('variation_prices.index') }}">
                <i class="fa fa-dashboard"></i> Variation Prices
            </a>
        </li>
        <li class="active">Create</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                Create New Variation Price
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('variation_prices.index') }}" class="btn btn-sm btn-info"
                   title="Show All Variation Price">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('variation_prices.store') }}" id="create_variation_price_form"
              name="create_variation_price_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('variation_prices.form', ['variationPrice' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Add variationPrice</button>
            </div>
        </form>
    </div>

@endsection