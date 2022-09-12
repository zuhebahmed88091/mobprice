@extends('layouts.app')

@section('content-header')
    <h1>Brand Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('brands.index') }}">
                <i class="fa fa-dashboard"></i> Brands
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($brand->title) ? ucfirst($brand->title) : 'Brand' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('brands.destroy', $brand->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('brands.index') }}" class="btn btn-sm btn-info" title="Show All Brand">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('brands.create') }}" class="btn btn-sm btn-success" title="Create New Brand">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('brands.edit', $brand->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit Brand">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete Brand"
                            onclick="return confirm('Delete Brand?')">
                        <i aria-hidden="true" class="fa fa-trash"></i>
                    </button>

                </form>

            </div>

        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th width="25%">Title</th>
                        <td width="75%">{{ $brand->title }}</td>
                    </tr>
                    <tr>
                        <th>Logo</th>
                        <td class="text-center">
                            <img src="{{ asset('storage/brands/' . optional($brand)->image) }}"
                                 alt="Brand Logo"
                                 style="width: 135px; height: 45px">
                        </td>
                    </tr>
                    <tr>
                        <th width="25%">Total Item</th>
                        <td width="75%">{{ $brand->total_item }}</td>
                    </tr>
                    <tr>
                        <th width="25%">Sorting</th>
                        <td width="75%">{{ $brand->sorting }}</td>
                    </tr>
                    <tr>
                        <th width="25%">Revision</th>
                        <td width="75%">{{ $brand->revision }}</td>
                    </tr>

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
