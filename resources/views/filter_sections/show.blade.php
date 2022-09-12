@extends('layouts.app')

@section('content-header')
    <h1>Filter Section Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('filter_sections.index') }}">
                <i class="fa fa-dashboard"></i> Filter Sections
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($filterSection->label) ? ucfirst($filterSection->label) : 'Filter Section' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('filter_sections.destroy', $filterSection->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('filter_sections.index') }}" class="btn btn-sm btn-info" title="Show All Filter Section">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('filter_sections.create') }}" class="btn btn-sm btn-success" title="Create New Filter Section">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('filter_sections.edit', $filterSection->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit Filter Section">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete Filter Section"
                            onclick="return confirm('Delete Filter Section?')">
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
                        <th>Filter Tab</th>
                        <td>{{ optional($filterSection->filterTab)->title }}</td>
                    </tr>
                    <tr>
                        <th>Label</th>
                        <td>{{ $filterSection->label }}</td>
                    </tr>
                    <tr>
                        <th>Field</th>
                        <td>{{ $filterSection->field }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ $filterSection->type }}</td>
                    </tr>
                    <tr>
                        <th>Show Label</th>
                        <td>{{ $filterSection->show_label }}</td>
                    </tr>
                    <tr>
                        <th>Sorting</th>
                        <td>{{ $filterSection->sorting }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $filterSection->status }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $filterSection->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $filterSection->updated_at }}</td>
                    </tr>

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
