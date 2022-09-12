@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Tag Details</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>Title</th>
            <td>{{ $tag->title }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $tag->status }}</td>
        </tr>
        <tr>
            <th>Created By</th>
            <td>{{ optional($tag->creator)->name }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $tag->created_at }}</td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td>{{ $tag->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
