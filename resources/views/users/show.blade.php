@extends('layouts.app')

@section('content-header')
    <h1>User Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('users.index') }}">
                <i class="fa fa-dashboard"></i> Users
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($user->name) ? ucfirst($user->name) : 'User' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('users.destroy', $user->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('users.index'))
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-info" title="Show All User">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('users.printDetails'))
                        <a href="{{ route('users.printDetails', $user->id) }}" class="btn btn-sm btn-warning"
                           title="Print Details">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('users.create'))
                        <a href="{{ route('users.create') }}" class="btn btn-sm btn-success" title="Create New User">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('users.edit'))
                        <a href="{{ route('users.edit', $user->id ) }}"
                           class="btn btn-sm btn-primary" title="Edit User">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('users.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="Delete User"
                                onclick="return confirm('Delete User?')">
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
                        <th>Photo</th>
                        <td>
                            <img src="{{ asset('storage/profiles/' . optional($user->uploadedFile)->filename) }}"
                                 alt="Profile Image"
                                 style="width: 128px; height: 128px">
                        </td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Email Verified At</th>
                        <td>{{ $user->email_verified_at }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $user->status }}</td>
                    </tr>
                    <tr>
                        <th>Roles</th>
                        <td>
                            {{ implode(', ', $user->roles()->pluck('name')->toArray()) }}
                        </td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{ optional($user->country)->country_name }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $user->phone }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $user->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $user->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
