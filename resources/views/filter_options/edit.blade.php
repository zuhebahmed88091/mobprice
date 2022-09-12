@extends('layouts.app')

@section('content-header')
    <h1>Edit Filter Option</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('filter_options.index') }}">
                <i class="fa fa-dashboard"></i> Filter Options
            </a>
        </li>
        <li class="active">Edit</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($filterOption->name) ? ucfirst($filterOption->name) : 'Filter Option' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('filter_options.index') }}" class="btn btn-sm btn-info"
                   title="Show All Filter Option">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('filter_options.create') }}" class="btn btn-sm btn-success"
                   title="Create New Filter Option">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('filter_options.update', $filterOption->id) }}"
              id="edit_filter_option_form"
              name="edit_filter_option_form" accept-charset="UTF-8" >
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

                @include ('filter_options.form', ['filterOption' => $filterOption,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Update</button>
            </div>
        </form>

    </div>

@endsection