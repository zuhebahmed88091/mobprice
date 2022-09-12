@extends('layouts.app')

@section('content-header')
    <h1>Edit User Opinions</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('opinions.index') }}">
                <i class="fa fa-dashboard"></i> Opinions
            </a>
        </li>
        <li class="active">Edit</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                Edit
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('opinions.index') }}" class="btn btn-sm btn-info"
                   title="Show All Opinions">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('opinions.update', $opinions->id) }}"
              id="edit_opinions_form"
              name="edit_opinions_form" accept-charset="UTF-8" >
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

                @include ('user_opinions.form', ['opinions' => $opinions,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Update</button>
            </div>
        </form>

    </div>

@endsection
