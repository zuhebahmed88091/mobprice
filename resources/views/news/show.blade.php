@extends('layouts.app')

@section('content-header')
    <h1>News Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('news.index') }}">
                <i class="fa fa-dashboard"></i> News
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($news->title) ? ucfirst($news->title) : 'News' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('news.destroy', $news->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('news.index') }}" class="btn btn-sm btn-info" title="Show All News">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('news.create') }}" class="btn btn-sm btn-success" title="Create New News">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('news.edit', $news->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit News">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete News"
                            onclick="return confirm('Delete News?')">
                        <i aria-hidden="true" class="fa fa-trash"></i>
                    </button>

                </form>

            </div>

        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-show">
                <tbody>
                    <tr>
                        <th>Title</th>
                        <td>{{ $news->title }}</td>
                    </tr>
                    <tr>
                        <th>Thamble</th>
                        <td class="text-center">
                            <img src="{{ asset('storage/news/' . optional($news)->image) }}"
                                 alt="Profile Image"
                                 style="width: 150px; height: 150px">
                        </td>
                    </tr>
                    <tr>
                        <th>Short Description </th>
                        <td>{{ $news->short_description }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $news->status }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $news->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $news->updated_at }}</td>
                    </tr>
                    
                    <tr>
                        <th>Description</th>
                        <td>{!! $news->description !!}</td>
                    </tr>
                   

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
