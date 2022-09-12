@extends('layouts.app')

@section('content-header')
    <h1>Create Module</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('modules.index') }}">
                <i class="fa fa-dashboard"></i> Modules
            </a>
        </li>
        <li class="active">Create</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                Create New Module
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('modules.index') }}" class="btn btn-sm btn-info"
                   title="Show All Module">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('modules.store') }}">
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('modules.form', ['module' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Add module</button>
            </div>
        </form>
    </div>

@endsection

