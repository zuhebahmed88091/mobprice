@extends('layouts.app')

@section('content-header')
    <h1>Filter Tab Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('filter_tabs.index') }}">
                <i class="fa fa-dashboard"></i> Filter Tabs
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($filterTab->title) ? ucfirst($filterTab->title) : 'Filter Tab' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('filter_tabs.destroy', $filterTab->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('filter_tabs.index') }}" class="btn btn-sm btn-info" title="Show All Filter Tab">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('filter_tabs.create') }}" class="btn btn-sm btn-success" title="Create New Filter Tab">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('filter_tabs.edit', $filterTab->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit Filter Tab">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete Filter Tab"
                            onclick="return confirm('Delete Filter Tab?')">
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
                        <th>Title</th>
                        <td>{{ $filterTab->title }}</td>
                    </tr>
                    <tr>
                        <th>Sorting</th>
                        <td>{{ $filterTab->sorting }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $filterTab->status }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $filterTab->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $filterTab->updated_at }}</td>
                    </tr>

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
