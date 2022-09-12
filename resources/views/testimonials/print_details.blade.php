@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Testimonial Details</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>Customer</th>
            <td>{{ optional($testimonial->customer)->name }}</td>
        </tr>
        <tr>
            <th>Rating</th>
            <td>{{ $testimonial->rating }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $testimonial->status }}</td>
        </tr>
        <tr>
            <th>Message</th>
            <td>{{ $testimonial->message }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $testimonial->created_at }}</td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td>{{ $testimonial->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
