@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-toggle/css/bootstrap-toggle.min.css') }}">

@endsection

@section('content-header')
    <h1>Quick Update Mobile</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('brands.index') }}">
                <i class="fa fa-dashboard"></i> Mobiles
            </a>
        </li>
        <li class="active">Quick Update</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($mobile->title) ? $mobile->title : 'Mobile' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('mobiles.index') }}" class="btn btn-sm btn-info"
                   title="Show All Mobile">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('mobiles.quick.update.store', $mobile->id) }}"
            id="quick_update_mobile_form"
            name="quick_update_mobile_form" ccept-charset="UTF-8" enctype="multipart/form-data">
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

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('official_link') ? 'has-error' : '' }}">
                            <label for="official_link">Official Link</label>
                            <input class="form-control" name="official_link" type="text" id="title"
                                value="{{ old('official_link', optional($mobile)->official_link) }}" minlength="1" maxlength="255" required>
                            {!! $errors->first('official_link', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('expert_score') ? 'has-error' : '' }}">
                            <label for="expert_score">Expert Score (Out of 10)</label>
                            <input class="form-control" name="expert_score" type="number" id="expert_score"
                                value="{{ old('expert_score', optional($mobile)->expert_score) }}" min="1" max="10" required>
                            {!! $errors->first('expert_score', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-2 text-center">
                        @if(!empty($mobile))
                            <label class="input-group-btn">
                                <img src="{{ asset('storage/mobiles/'. $mobile->id . '/' . optional($mobile)->default_image ) }}?no-cache={{ time() }}"
                                    alt="default_image"
                                    style="width: 100px; height: 150px;margin-right: 10px;">
                            </label>
                        @endif
                    </div>
                    <div class="col-md-4" style=margin-top: 34px;>
                        <div class="form-group {{ $errors->has('default_image') ? 'has-error' : '' }}">
                            <label for="default_image">Default Image </label>
                            <div class="input-group">

                                <input value="{{ !empty($mobile) ? optional($mobile->mobileImages()->first())->filename : '' }}"
                                       type="text"
                                       id="default_image"
                                       class="form-control" readonly required>
                                <label class="input-group-btn">
                                    <span class="btn btn-warning">
                                        Browse&hellip; <input type="file" name="default_image" style="display: none;">
                                    </span>
                                </label>
                            </div>
                            {!! $errors->first('default_image', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-3" style="display: flex; flex-direction:column">
                        <div ><label for="published">Published</label></div>
                             <div>
                                <input type="hidden" name="published" value="0">
                                <input name="published" type="checkbox" value="1"
                                                       {{ optional($mobile)->published != 0 ? 'checked' : '' }}
                                                       data-toggle="toggle"
                                                       data-on="Yes"
                                                       data-off="No"
                                                       data-onstyle="success"
                                                       aria-label="status">
                             </div>


                    </div>
                    <div class="col-md-3" style="display: flex; flex-direction:column">
                        <div><label for="completed">Completed</label></div>
                        <div>
                            <input type="hidden" name="completed" value="0">
                                            <input name="completed" type="checkbox" value="1"
                                                   {{ optional($mobile)->completed != 0 ? 'checked' : '' }}
                                                   data-toggle="toggle"
                                                   data-on="Yes"
                                                   data-off="No"
                                                   data-onstyle="success"
                                                   aria-label="status">
                        </div>


                    </div>
                </div>

            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Update</button>
            </div>
        </form>
    </div>

@endsection

@section('javascript')
    <script src="{{ asset('bootstrap-toggle/js/bootstrap-toggle.min.js') }}"></script>

@endsection
