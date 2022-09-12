@extends('layouts.app')

@section('content-header')
    <h1>Edit Mobile Region</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('mobile_regions.index') }}">
                <i class="fa fa-dashboard"></i> Mobile Regions
            </a>
        </li>
        <li class="active">Edit</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($mobileRegion->title) ? ucfirst($mobileRegion->title) : 'Mobile Region' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('mobile_regions.index') }}" class="btn btn-sm btn-info"
                   title="Show All Mobile Region">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('mobile_regions.create') }}" class="btn btn-sm btn-success"
                   title="Create New Mobile Region">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('mobile_regions.update', $mobileRegion->id) }}"
              id="edit_mobile_region_form"
              name="edit_mobile_region_form" accept-charset="UTF-8" >
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            <div class="box-body">

                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('mobile_regions.form', ['mobileRegion' => $mobileRegion,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Update</button>
            </div>
        </form>

    </div>

@endsection