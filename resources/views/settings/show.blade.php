@extends('layouts.app')

@section('content-header')
    <h1>Setting Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('settings.index') }}">
                <i class="fa fa-dashboard"></i> Settings
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($setting->title) ? ucfirst($setting->title) : 'Setting' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('settings.destroy', $setting->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('settings.index') }}" class="btn btn-sm btn-info" title="Show All Setting">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('settings.create') }}" class="btn btn-sm btn-success" title="Create New Setting">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('settings.edit', $setting->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit Setting">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete Setting"
                            onclick="return confirm('Delete Setting?')">
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
                        <td width="75%">{{ $setting->title }}</td>
                    </tr>
                    <tr>
                        <th width="25%">Constant</th>
                        <td width="75%">{{ $setting->constant }}</td>
                    </tr>
                    <tr>
                        <th width="25%">Value</th>
                        <td width="75%">{{ $setting->value }}</td>
                    </tr>
                    <tr>
                        <th width="25%">Field Type</th>
                        <td width="75%">{{ $setting->field_type }}</td>
                    </tr>
                    <tr>
                        <th width="25%">Options</th>
                        <td width="75%">{{ $setting->options }}</td>
                    </tr>
                    <tr>
                        <th width="25%">Status</th>
                        <td width="75%">{{ $setting->status }}</td>
                    </tr>

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection