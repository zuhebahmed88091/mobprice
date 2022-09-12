@extends('layouts.app')

@section('content-header')
    <h1>Edit Tag</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('tags.index') }}">
                <i class="fa fa-dashboard"></i> Tags
            </a>
        </li>
        <li class="active">Edit</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($tag->title) ? ucfirst($tag->title) : 'Tag' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('tags.index') }}" class="btn btn-sm btn-info"
                   title="Show All Tag">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('tags.create') }}" class="btn btn-sm btn-success"
                   title="Create New Tag">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('tags.update', $tag->id) }}"
              id="edit_tag_form"
              name="edit_tag_form" accept-charset="UTF-8" >
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

                @include ('tags.form', ['tag' => $tag,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Update</button>
            </div>
        </form>

    </div>

@endsection