@extends('layouts.app')

@section('content-header')
    <h1>Edit Event Log</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('event_logs.index') }}">
                <i class="fa fa-dashboard"></i> Event Logs
            </a>
        </li>
        <li class="active">Edit</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($title) ? ucfirst($title) : 'Event Log' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('event_logs.index') }}" class="btn btn-sm btn-info"
                   title="Show All Event Log">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('event_logs.create') }}" class="btn btn-sm btn-success"
                   title="Create New Event Log">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('event_logs.update', $eventLog->id) }}"
              id="edit_event_log_form"
              name="edit_event_log_form" accept-charset="UTF-8" >
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

                @include ('event_logs.form', ['eventLog' => $eventLog,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Update</button>
            </div>
        </form>

    </div>

@endsection