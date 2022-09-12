@extends('layouts.app')

@section('content-header')
    <h1>Comment Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('comments.index') }}">
                <i class="fa fa-dashboard"></i> Comments
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Comment' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('comments.destroy', $comment->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('comments.index') }}" class="btn btn-sm btn-info" title="Show All Comment">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('comments.create') }}" class="btn btn-sm btn-success" title="Create New Comment">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('comments.edit', $comment->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit Comment">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete Comment"
                            onclick="return confirm('Delete Comment?')">
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
                        <th>Ticket</th>
                        <td>{{ optional($comment->ticket)->subject }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ optional($comment->user)->name }}</td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td>{{ $comment->message }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $comment->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $comment->updated_at }}</td>
                    </tr>

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
