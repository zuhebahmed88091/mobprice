@extends('layouts.app')

@section('content-header')
    <h1>Filter Option Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('filter_options.index') }}">
                <i class="fa fa-dashboard"></i> Filter Options
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($filterOption->name) ? ucfirst($filterOption->name) : 'Filter Option' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('filter_options.destroy', $filterOption->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('filter_options.index') }}" class="btn btn-sm btn-info" title="Show All Filter Option">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('filter_options.create') }}" class="btn btn-sm btn-success" title="Create New Filter Option">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('filter_options.edit', $filterOption->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit Filter Option">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete Filter Option"
                            onclick="return confirm('Delete Filter Option?')">
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
                        <th>Filter Section</th>
                        <td>{{ optional($filterOption->filterSection)->label }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $filterOption->name }}</td>
                    </tr>
                    <tr>
                        <th>Value</th>
                        <td>{{ $filterOption->value }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $filterOption->status }}</td>
                    </tr>
                    <tr>
                        <th>Sorting</th>
                        <td>{{ $filterOption->sorting }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $filterOption->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $filterOption->updated_at }}</td>
                    </tr>

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
