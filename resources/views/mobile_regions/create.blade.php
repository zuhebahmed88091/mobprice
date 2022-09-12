@extends('layouts.app')

@section('content-header')
    <h1>Create Mobile Region</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('mobile_regions.index') }}">
                <i class="fa fa-dashboard"></i> Mobile Regions
            </a>
        </li>
        <li class="active">Create</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                Create New Mobile Region
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('mobile_regions.index') }}" class="btn btn-sm btn-info"
                   title="Show All Mobile Region">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('mobile_regions.store') }}" id="create_mobile_region_form"
              name="create_mobile_region_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('mobile_regions.form', ['mobileRegion' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Add mobileRegion</button>
            </div>
        </form>
    </div>

@endsection