@extends('layouts.app')

@section('content-header')
    <h1>Module Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('modules.index') }}">
                <i class="fa fa-dashboard"></i> Modules
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($module->name) ? ucfirst($module->name) : 'Module' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('modules.destroy', $module->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('modules.index'))
                        <a href="{{ route('modules.index') }}" class="btn btn-sm btn-info" title="Show All Module">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('modules.printDetails'))
                        <a href="{{ route('modules.printDetails', $module->id) }}" class="btn btn-sm btn-warning"
                           title="Print Details">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('modules.create'))
                        <a href="{{ route('modules.create') }}" class="btn btn-sm btn-success"
                           title="Create New Module">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('modules.edit'))
                        <a href="{{ route('modules.edit', $module->id ) }}"
                           class="btn btn-sm btn-primary" title="Edit Module">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('modules.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="Delete Module"
                                onclick="return confirm('Delete Module?')">
                            <i aria-hidden="true" class="fa fa-trash"></i>
                        </button>
                    @endif

                </form>

            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-show">
                    <tbody>
                    <tr>
                        <th>Name</th>
                        <td>{{ $module->name }}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td>{{ $module->slug }}</td>
                    </tr>
                    <tr>
                        <th>Fa Icon</th>
                        <td>{{ $module->fa_icon }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $module->status }}</td>
                    </tr>
                    <tr>
                        <th>Sorting</th>
                        <td>{{ $module->sorting }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $module->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $module->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
