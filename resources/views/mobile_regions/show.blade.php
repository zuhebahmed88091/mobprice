@extends('layouts.app')

@section('content-header')
    <h1>Mobile Region Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('mobile_regions.index') }}">
                <i class="fa fa-dashboard"></i> Mobile Regions
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($mobileRegion->title) ? ucfirst($mobileRegion->title) : 'Mobile Region' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('mobile_regions.destroy', $mobileRegion->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('mobile_regions.index') }}" class="btn btn-sm btn-info" title="Show All Mobile Region">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('mobile_regions.create') }}" class="btn btn-sm btn-success" title="Create New Mobile Region">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('mobile_regions.edit', $mobileRegion->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit Mobile Region">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete Mobile Region"
                            onclick="return confirm('Delete Mobile Region?')">
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
                        <td>{{ $mobileRegion->title }}</td>
                    </tr>
                    <tr>
                        <th>Currency</th>
                        <td>{{ $mobileRegion->currency }}</td>
                    </tr>
                    <tr>
                        <th>Iso Code</th>
                        <td>{{ $mobileRegion->iso_code }}</td>
                    </tr>
                    <tr>
                        <th>Symbol</th>
                        <td>{{ $mobileRegion->symbol }}</td>
                    </tr>
                    <tr>
                        <th>Rate</th>
                        <td>{{ $mobileRegion->rate }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $mobileRegion->status }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $mobileRegion->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $mobileRegion->updated_at }}</td>
                    </tr>

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
