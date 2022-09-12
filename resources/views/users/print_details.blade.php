@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">User Details</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>Photo</th>
            <td>
                <img src="{{ $_SERVER['DOCUMENT_ROOT'] . '/storage/profiles/' . optional($user->uploadedFile)->filename }}"
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
            <th>Department</th>
            <td>{{ optional($user->department)->name }}</td>
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

@endsection
