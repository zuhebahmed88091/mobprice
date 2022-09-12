@extends('layouts.app')

@section('content-header')
    <h1>Edit Comment</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('comments.index') }}">
                <i class="fa fa-dashboard"></i> Comments
            </a>
        </li>
        <li class="active">Edit</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($title) ? ucfirst($title) : 'Comment' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('comments.index') }}" class="btn btn-sm btn-info"
                   title="Show All Comment">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('comments.create') }}" class="btn btn-sm btn-success"
                   title="Create New Comment">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('comments.update', $comment->id) }}"
              id="edit_comment_form"
              name="edit_comment_form" accept-charset="UTF-8" >
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

                @include ('comments.form', ['comment' => $comment,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Update</button>
            </div>
        </form>

    </div>

@endsection