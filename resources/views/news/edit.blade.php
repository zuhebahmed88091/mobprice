@extends('layouts.app')

@section('content-header')
    <h1>Edit News</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('news.index') }}">
                <i class="fa fa-dashboard"></i> News
            </a>
        </li>
        <li class="active">Edit</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($news->title) ? ucfirst($news->title) : 'News' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('news.index') }}" class="btn btn-sm btn-info"
                   title="Show All News">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('news.create') }}" class="btn btn-sm btn-success"
                   title="Create New News">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('news.update', $news->id) }}"
              id="edit_mobile_ram_form"
              name="edit_mobile_ram_form" accept-charset="UTF-8" enctype="multipart/form-data">
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

                @include ('news.form', ['news' => $news,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Update</button>
            </div>
        </form>

    </div>

@endsection