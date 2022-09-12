@extends('layouts.app')

@section('content-header')
    <h1>Create Contact Us</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('contact_us.index') }}">
                <i class="fa fa-dashboard"></i> Contact Us
            </a>
        </li>
        <li class="active">Create</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                Create New Contact Us
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('contact_us.index') }}" class="btn btn-sm btn-info"
                   title="Show All Contact Us">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('contact_us.store') }}" id="create_contact_us_form"
              name="create_contact_us_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('contact_us.form', ['contactUs' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Add Message</button>
            </div>
        </form>
    </div>

@endsection
