@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Module Details</h3>
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

@endsection
