@extends('layouts.app')

@section('content-header')
    <h1>Create Uploaded File</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('uploaded_files.index') }}">
                <i class="fa fa-dashboard"></i> Uploaded Files
            </a>
        </li>
        <li class="active">Create</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                Create New Uploaded File
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('uploaded_files.index') }}" class="btn btn-sm btn-info"
                   title="Show All Uploaded File">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('uploaded_files.store') }}" id="create_uploaded_file_form"
              name="create_uploaded_file_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('uploaded_files.form', ['uploadedFile' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Add uploadedFile</button>
            </div>
        </form>
    </div>

@endsection
