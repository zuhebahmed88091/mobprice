@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Country Details</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>Country Name</th>
            <td>{{ $country->country_name }}</td>
        </tr>

        <tr>
            <th>Country Code</th>
            <td>{{ $country->country_code }}</td>
        </tr>

        <tr>
            <th>Currency Code</th>
            <td>{{ $country->currency_code }}</td>
        </tr>

        <tr>
            <th>Capital</th>
            <td>{{ $country->capital }}</td>
        </tr>

        <tr>
            <th>Continent Name</th>
            <td>{{ $country->continent_name }}</td>
        </tr>

        <tr>
            <th>Continent Code</th>
            <td>{{ $country->continent_code }}</td>
        </tr>

        <tr>
            <th>Status</th>
            <td>{{ $country->status }}</td>
        </tr>


        </tbody>
    </table>

@endsection
