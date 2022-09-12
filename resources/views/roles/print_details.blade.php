@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Role Details</h3>
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th width="25%">Name</th>
            <td width="75%">{{ $role->name }}</td>
        </tr>
        <tr>
            <th width="25%">Display Name</th>
            <td width="75%">{{ $role->display_name }}</td>
        </tr>
        <tr>
            <th width="25%">Description</th>
            <td width="75%">{{ $role->description }}</td>
        </tr>
        <tr>
            <th width="25%">Created At</th>
            <td width="75%">{{ $role->created_at }}</td>
        </tr>
        <tr>
            <th width="25%">Updated At</th>
            <td width="75%">{{ $role->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
