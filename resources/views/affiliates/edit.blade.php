@extends('layouts.app')

@section('content-header')
    <h1>Edit Affiliate</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('affiliates.index') }}">
                <i class="fa fa-dashboard"></i> Affiliates
            </a>
        </li>
        <li class="active">Edit</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($affiliate->title) ? ucfirst($affiliate->title) : 'Affiliate' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('affiliates.index') }}" class="btn btn-sm btn-info"
                   title="Show All Affiliate">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('affiliates.create') }}" class="btn btn-sm btn-success"
                   title="Create New Affiliate">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('affiliates.update', $affiliate->id) }}"
              id="edit_affiliate_form"
              name="edit_affiliate_form" accept-charset="UTF-8" >
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

                @include ('affiliates.form', ['affiliate' => $affiliate,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Update</button>
            </div>
        </form>

    </div>

@endsection