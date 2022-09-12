@extends('layouts.app')

@section('content-header')
    <h1>Edit Filter Tab</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('filter_tabs.index') }}">
                <i class="fa fa-dashboard"></i> Filter Tabs
            </a>
        </li>
        <li class="active">Edit</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($filterTab->title) ? ucfirst($filterTab->title) : 'Filter Tab' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('filter_tabs.index') }}" class="btn btn-sm btn-info"
                   title="Show All Filter Tab">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('filter_tabs.create') }}" class="btn btn-sm btn-success"
                   title="Create New Filter Tab">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('filter_tabs.update', $filterTab->id) }}"
              id="edit_filter_tab_form"
              name="edit_filter_tab_form" accept-charset="UTF-8" >
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

                @include ('filter_tabs.form', ['filterTab' => $filterTab,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Update</button>
            </div>
        </form>

    </div>

@endsection