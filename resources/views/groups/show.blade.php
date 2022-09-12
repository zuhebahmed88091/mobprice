@extends('layouts.app')

@section('content-header')
    <h1>Group Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('groups.index') }}">
                <i class="fa fa-dashboard"></i> Groups
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($group->title) ? ucfirst($group->title) : 'Group' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('groups.destroy', $group->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('groups.index') }}" class="btn btn-sm btn-info" title="Show All Group">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('groups.create') }}" class="btn btn-sm btn-success" title="Create New Group">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('groups.edit', $group->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit Group">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete Group"
                            onclick="return confirm('Delete Group?')">
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
                        <td>{{ $group->title }}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td>{{ $group->slug }}</td>
                    </tr>
                    <tr>
                        <th>Fa Icon</th>
                        <td>{{ $group->fa_icon }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $group->status }}</td>
                    </tr>
                    <tr>
                        <th>Short Description</th>
                        <td>{{ $group->short_description }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $group->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $group->updated_at }}</td>
                    </tr>

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
