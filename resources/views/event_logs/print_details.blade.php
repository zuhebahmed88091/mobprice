@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Event Details</h3>
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th width="25%">User</th>
            <td width="75%">{{ optional($eventLog->user)->name }}</td>
        </tr>
        <tr>
            <th width="25%">End Point</th>
            <td width="75%">{{ $eventLog->end_point }}</td>
        </tr>
        <tr>
            <th width="25%">Changes</th>
            <td width="75%">{!! $eventLog->changes !!}</td>
        </tr>
        <tr>
            <th width="25%">Created At</th>
            <td width="75%">{{ $eventLog->created_at }}</td>
        </tr>
        <tr>
            <th width="25%">Updated At</th>
            <td width="75%">{{ $eventLog->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
