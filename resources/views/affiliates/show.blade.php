@extends('layouts.app')

@section('content-header')
    <h1>Affiliate Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('affiliates.index') }}">
                <i class="fa fa-dashboard"></i> Affiliates
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($affiliate->title) ? ucfirst($affiliate->title) : 'Affiliate' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('affiliates.destroy', $affiliate->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('affiliates.index') }}" class="btn btn-sm btn-info" title="Show All Affiliate">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('affiliates.create') }}" class="btn btn-sm btn-success" title="Create New Affiliate">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('affiliates.edit', $affiliate->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit Affiliate">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete Affiliate"
                            onclick="return confirm('Delete Affiliate?')">
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
                        <td>{{ $affiliate->title }}</td>
                    </tr>
                    <tr>
                        <th>Domain</th>
                        <td>{{ $affiliate->domain }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $affiliate->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $affiliate->updated_at }}</td>
                    </tr>

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
