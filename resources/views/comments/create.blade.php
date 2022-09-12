@extends('layouts.app')

@section('content-header')
    <h1>Create Comment</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('comments.index') }}">
                <i class="fa fa-dashboard"></i> Comments
            </a>
        </li>
        <li class="active">Create</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                Create New Comment
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('comments.index') }}" class="btn btn-sm btn-info"
                   title="Show All Comment">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('comments.store') }}" id="create_comment_form"
              name="create_comment_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('comments.form', ['comment' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Add comment</button>
            </div>
        </form>
    </div>

@endsection