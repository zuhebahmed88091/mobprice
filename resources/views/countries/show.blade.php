@extends('layouts.app')

@section('content-header')
    <h1>Country Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('countries.index') }}">
                <i class="fa fa-dashboard"></i> Countries
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Country' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('countries.destroy', $country->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('countries.index'))
                        <a href="{{ route('countries.index') }}" class="btn btn-sm btn-info" title="Show All Country">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('countries.printDetails'))
                        <a href="{{ route('countries.printDetails', $country->id) }}" class="btn btn-sm btn-warning"
                           title="Print Details">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('countries.create'))
                        <a href="{{ route('countries.create') }}" class="btn btn-sm btn-success"
                           title="Create New Country">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('countries.edit'))
                        <a href="{{ route('countries.edit', $country->id ) }}"
                           class="btn btn-sm btn-primary" title="Edit Country">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('countries.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="Delete Country"
                                onclick="return confirm('Delete Country?')">
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
            </div>
        </div>
    </div>

@endsection
