@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-toggle/css/bootstrap-toggle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
@endsection

@section('content-header')
    <h1>Site Settings</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('settings.index') }}">
                <i class="fa fa-dashboard"></i> Settings
            </a>
        </li>
        <li class="active">Update</li>
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

    <div class="row">
        <div class="col-md-3">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">All Groups</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        @foreach($groups as $group)
                            <li {{ $group->id == $selectedGroup->id ? 'class=active' : '' }}>
                                <a href="{{ route('settings.group', $group->id) }}">
                                    <i class="fa {{ $group->fa_icon ? $group->fa_icon : 'fa-cog' }}"></i>
                                    {{ $group->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="box box-default">

                <div class="box-header with-border">
                    <h3 class="box-title">
                        {{ $selectedGroup->title }} Settings
                    </h3>
                </div>

                <form method="POST" action="{{ route('settings.update_batch') }}" accept-charset="UTF-8"
                      class="form-horizontal"
                      enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PUT">
                    <input name="groupId" type="hidden" value="{{ $selectedGroup->id }}">
                    {{ csrf_field() }}

                    <div class="box-body">
                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if(count($settings) == 0)
                            <div class="panel-body text-center">
                                <h4>No Settings Available!</h4>
                            </div>
                        @else

                            @foreach($settings as $setting)
                                <div class="form-group">
                                    <label for="{{ $setting->constant }}"
                                           class="control-label col-lg-4">{{ $setting->title }}</label>
                                    <div class="col-lg-6">
                                        @if ($setting->field_type == 'Select')
                                            <select class="form-control select-admin-lte" name="{{ $setting->constant }}"
                                                    id="{{ $setting->constant }}">
                                                @foreach($setting->options as $key => $display_name)
                                                    <option
                                                        value="{{ $key }}" {{ ($key == $setting->value) ? 'selected' : '' }}>
                                                        {{ $display_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif ($setting->field_type == 'MultiSelect')
                                            <select class="form-control select-admin-lte"
                                                    data-placeholder="Select Multiple"
                                                    multiple="multiple"
                                                    name="{{ $setting->constant }}[]"
                                                    id="{{ $setting->constant }}">
                                                @foreach($setting->options as $key => $display_name)
                                                    <option value="{{ $key }}"
                                                        {{ (in_array($key, explode('|', $setting->value))) ? 'selected' : '' }}>
                                                        {{ $display_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif ($setting->field_type == 'Boolean')
                                            <input type="hidden" name="{{ $setting->constant }}" value="0">
                                            <input name="{{ $setting->constant }}" type="checkbox" value="1"
                                                   {{ optional($setting)->value != 0 ? 'checked' : '' }}
                                                   data-toggle="toggle"
                                                   data-on="{{ $setting->dataOn }}"
                                                   data-off="{{ $setting->dataOff }}"
                                                   data-onstyle="success"
                                                   aria-label="status">
                                        @elseif ($setting->field_type == 'Textarea')
                                            <textarea class="form-control" name="{{ $setting->constant }}"
                                                      rows="{{ $setting->options }}"
                                                      id="{{ $setting->constant }}"
                                                      aria-label="text-area"
                                                      required>{{ old('constant', optional($setting)->value) }}</textarea>

                                        @elseif ($setting->field_type == 'ColorPicker')
                                            <div class="input-group input-colorpicker">
                                                <input class="form-control input-colorpicker" name="{{ $setting->constant }}"
                                                       id="{{ $setting->constant }}"
                                                       value="{{ old('constant', optional($setting)->value) }}"
                                                       aria-label="color-picker">
                                                <div class="input-group-addon">
                                                    <i></i>
                                                </div>
                                            </div>
                                        @elseif ($setting->field_type == 'ProductList')
                                            <select class="form-control select-admin-lte" name="{{ $setting->constant }}"
                                                    id="{{ $setting->constant }}">
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        {{ ($product->id == $setting->value) ? 'selected' : '' }}>
                                                        {{ $product->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif ($setting->field_type == 'DateFormat')
                                            <select class="form-control select-admin-lte" name="{{ $setting->constant }}"
                                                    id="{{ $setting->constant }}">
                                                @foreach($setting->options as $option)
                                                    <option value="{{ $option }}"
                                                        {{ ($option == $setting->value) ? 'selected' : '' }}>
                                                        {{ $option }} ({{ date($option, time()) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif ($setting->field_type == 'File')
                                            <div class="input-group">
                                                @if(!empty($setting->value))
                                                    <label class="input-group-btn">
                                                        <img
                                                            src="{{ asset('storage/sites/' . $setting->value) }}"
                                                            alt="Logo Image"
                                                            style="width: 34px; height: 34px;margin-right: 10px;">
                                                    </label>
                                                @endif
                                                <input
                                                    value="{{ $setting->value }}"
                                                    type="text"
                                                    id="{{ $setting->constant }}"
                                                    class="form-control"
                                                    readonly {{ empty($setting->value) ? 'required' : '' }}>
                                                <label class="input-group-btn">
                                                    <span class="btn btn-warning">
                                                        Browse&hellip; <input type="file"
                                                                              name="{{ $setting->constant }}"
                                                                              style="display: none;">
                                                    </span>
                                                </label>
                                            </div>
                                        @else
                                            <input class="form-control" name="{{ $setting->constant }}"
                                                   id="{{ $setting->constant }}"
                                                   value="{{ old('constant', optional($setting)->value) }}"
                                                   minlength="1"
                                                   maxlength="255" required/>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                        @endif

                    </div>

                    @if(count($settings) > 0)
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right">Update settings</button>
                        </div>
                    @endif
                </form>
            </div>

        </div>
    </div>

@endsection

@section('javascript')
    <script src="{{ asset('bootstrap-toggle/js/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ asset('bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('js/article.js') }}"></script>
    <script>

        $(document).ready(function () {
            // color picker with addon
            $('.input-colorpicker').colorpicker()
        });

    </script>
@endsection
