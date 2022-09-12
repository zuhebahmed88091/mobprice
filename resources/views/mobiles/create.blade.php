@extends('layouts.app')

@section('content-header')
    <h1>Add Mobile</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('mobiles.index') }}">
                <i class="fa fa-dashboard"></i> Mobiles
            </a>
        </li>
        <li class="active">add</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                Create New Mobile
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('mobiles.index') }}" class="btn btn-sm btn-info"
                   title="Show All Mobile">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" class="form-horizontal" action="{{ route('mobiles.store') }}" id="create_mobile_form"
              name="create_mobile_form">
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('mobiles.form', ['mobile' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Add Mobile</button>
            </div>
        </form>
    </div>

@endsection
