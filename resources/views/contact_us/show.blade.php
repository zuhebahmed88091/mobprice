@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/summernote/summernote.css') }}">
@endsection

@section('content-header')
    <h1>Contact Us Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('contact_us.index') }}">
                <i class="fa fa-dashboard"></i> Contact Us
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $contactUs->subject }}</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body no-padding">
            <div class="mailbox-read-info">
                <h5>
                    From: {{ $contactUs->email }} - {{ $contactUs->full_name }}
                    <span class="mailbox-read-time pull-right">{{ $contactUs->created_at }}</span>
                </h5>
            </div>

            <div class="mailbox-read-message">
                {!! nl2br($contactUs->message) !!}
            </div>
        </div>

        <div class="box-footer">
            <form method="POST"
                  action="{!! route('contact_us.destroy', $contactUs->id) !!}"
                  accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}
                <a href="{{ route('contact_us.index') }}" class="btn btn-sm btn-primary" title="Show All Contact Us">
                    <i aria-hidden="true" class="fa fa-arrow-left"></i> Go Back
                </a>

                <button type="submit" class="btn btn-sm btn-danger pull-right"
                        title="Delete Contact Us"
                        onclick="return confirm('Delete Contact Us?')">
                    <i aria-hidden="true" class="fa fa-trash"></i>
                </button>

            </form>
        </div>
    </div>

    @foreach($replies as $reply)
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">You Replied to {{ $contactUs->email }} - {{ $contactUs->full_name }}</h3>

                <div class="pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                {!! nl2br($reply->message) !!}
            </div>
        </div>
    @endforeach

    <div class="box collapsed-box">
        <div class="box-header with-border">
            <h3 class="box-title">Reply to {{ $contactUs->email }} - {{ $contactUs->full_name }}</h3>

            <div class="pull-right">
                <button type="button" class="btn btn-success btn-sm btn-flat"
                        data-widget="collapse">
                    <i class="fa fa-reply"></i> YOUR REPLY
                </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="display: none;">

            <form method="POST" id="formReplyComment"
                  action="{{ route('contact_us.storeReply') }}"
                  accept-charset="UTF-8">
                {{ csrf_field() }}

                <input type="hidden" name="full_name" value="Support">
                <input type="hidden" name="email" value="{{ config('settings.SITE_EMAIL') }}">
                <input type="hidden" name="replied_id" value="{{ $contactUs->id }}">

                <textarea class="form-control rich-textarea"
                          aria-label="reply"
                          name="message" cols="50" rows="10"
                          id="message"></textarea>
            </form>
        </div>
        <!-- ./box-body -->
        <div class="box-footer">
            <button type="button" class="btn btn-primary btn-sm pull-right btn-reply">
                SEND REPLY
            </button>
            <a class="btn btn-danger btn-sm" data-widget="collapse">DISCARD</a>
        </div>
    </div>

@endsection

@section('javascript')
<script src="{{ asset('vendor/summernote/summernote.min.js') }}"></script>
<script src="{{ asset('vendor/summernote/summernote.settings.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.btn-reply').click(function () {
                if ($('#message').val()) {
                    $('#formReplyComment').submit();
                }
            });
        })
    </script>
@endsection



