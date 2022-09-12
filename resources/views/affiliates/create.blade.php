@extends('layouts.app')

@section('content-header')
    <h1>Create Affiliate</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('affiliates.index') }}">
                <i class="fa fa-dashboard"></i> Affiliates
            </a>
        </li>
        <li class="active">Create</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                Create New Affiliate
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('affiliates.index') }}" class="btn btn-sm btn-info"
                   title="Show All Affiliate">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('affiliates.store') }}" id="create_affiliate_form"
              name="create_affiliate_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('affiliates.form', ['affiliate' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Add affiliate</button>
            </div>
        </form>
    </div>

@endsection