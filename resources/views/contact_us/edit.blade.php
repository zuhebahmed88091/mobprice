@extends('layouts.app')

@section('content-header')
    <h1>Edit Contact Us</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('contact_us.index') }}">
                <i class="fa fa-dashboard"></i> Contact Uses
            </a>
        </li>
        <li class="active">Edit</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($contactUs->subject) ? ucfirst($contactUs->subject) : 'Contact Us' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('contact_us.index') }}" class="btn btn-sm btn-info"
                   title="Show All Contact Us">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('contact_us.create') }}" class="btn btn-sm btn-success"
                   title="Create New Contact Us">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('contact_us.update', $contactUs->id) }}"
              id="edit_contact_us_form"
              name="edit_contact_us_form" accept-charset="UTF-8" >
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            <div class="box-body">

                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('contact_us.form', ['contactUs' => $contactUs,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Update</button>
            </div>
        </form>

    </div>

@endsection
