@extends('layouts.app')

@section('content-header')
    <h1>Create Filter Tab</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('filter_tabs.index') }}">
                <i class="fa fa-dashboard"></i> Filter Tabs
            </a>
        </li>
        <li class="active">Create</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                Create New Filter Tab
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('filter_tabs.index') }}" class="btn btn-sm btn-info"
                   title="Show All Filter Tab">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('filter_tabs.store') }}" id="create_filter_tab_form"
              name="create_filter_tab_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('filter_tabs.form', ['filterTab' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Add filterTab</button>
            </div>
        </form>
    </div>

@endsection