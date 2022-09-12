@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Permission Details</h3>
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th width="25%">Module</th>
            <td width="75%">{{ optional($permission->module)->name }}</td>
        </tr>
        <tr>
            <th width="25%">Name</th>
            <td width="75%">{{ $permission->name }}</td>
        </tr>
        <tr>
            <th width="25%">Display Name</th>
            <td width="75%">{{ $permission->display_name }}</td>
        </tr>
        <tr>
            <th width="25%">Description</th>
            <td width="75%">{{ $permission->description }}</td>
        </tr>
        <tr>
            <th width="25%">Created At</th>
            <td width="75%">{{ $permission->created_at }}</td>
        </tr>
        <tr>
            <th width="25%">Updated At</th>
            <td width="75%">{{ $permission->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
