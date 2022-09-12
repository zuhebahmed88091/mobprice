@extends('layouts.app')

@section('title', 'Admin - Mobile Price')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/laravel-filemanager/css/cropper.min.css') }}">
    <style>
        {!! \File::get(base_path('vendor/unisharp/laravel-filemanager/public/css/lfm.css')) !!}

        .box-header.with-border.large > .box-tools {
            top: 10px;
        }

        .box-header.with-border.large {
            padding: 18px;
        }

        .select2-search.select2-search--dropdown {
            display: none;
        }

        label.section {
            text-transform: uppercase;
            color: #999999;
            font-size: 13px;
            font-weight: 600;
            margin: 0 0 10px 0;
        }

    </style>
    <link rel="stylesheet" href="{{ asset('vendor/laravel-filemanager/css/mfb.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laravel-filemanager/css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laravel-filemanager/jquery-ui-1.12.1.custom/jquery-ui.min.css') }}">
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border large">
            <h3 class="box-title">
                {{ trans('laravel-filemanager::lfm.title-panel') }}
            </h3>

            <div class="box-tools pull-right">

                <a id="to-previous" class="btn btn-primary btn-flat clickable hide">
                    <i class="fa fa-arrow-left"></i>
                </a>

                <div class="btn-group" style="margin-left: 10px;">
                    <a href="#" id="add-folder" class="btn btn-primary btn-flat"
                       data-mfb-label="{{ trans('laravel-filemanager::lfm.nav-new') }}">
                        <i class="fa fa-folder"></i> Add  Folder
                    </a>

                    <a href="#" id="upload" class="btn btn-warning btn-flat"
                       data-mfb-label="{{ trans('laravel-filemanager::lfm.nav-upload') }}">
                        <i class="fa fa-upload"></i> Upload
                    </a>
                </div>

                <div class="btn-group" style="margin-left: 10px;">
                    <a id="thumbnail-display" class="btn btn-info btn-flat clickable">
                        <i class="fa fa-th-large"></i>
                    </a>

                    <a id="list-display" class="btn btn-primary btn-flat clickable">
                        <i class="fa fa-list"></i>
                    </a>
                </div>

            </div>

        </div>

        <div class="box-body">

            <div class="row">
                <div class="col-sm-2 hidden-xs">
                    <div id="tree" style="margin-bottom: 10px;"></div>

                    <form>
                        <label for="order_by" class="section">
                            {{ trans('laravel-filemanager::lfm.nav-sort') }}
                        </label>
                        <select class="form-control select-admin-lte" id="order_by" name="order_by">
                            <option value="alphabetic">
                                {{ trans('laravel-filemanager::lfm.nav-sort-alphabetic') }}
                            </option>
                            <option value="size">{{ trans('laravel-filemanager::lfm.nav-sort-size') }}</option>
                            <option value="time">{{ trans('laravel-filemanager::lfm.nav-sort-time') }}</option>
                        </select>

                        <div class="clearfix" style="height: 15px;"></div>

                        <label for="sort_direction" class="section">
                            {{ trans('laravel-filemanager::lfm.title-direction') }}
                        </label>
                        <select class="form-control select-admin-lte" id="sort_direction" name="sort_direction">
                            <option value="ASC">{{ trans('laravel-filemanager::lfm.sort-direction-asc') }}</option>
                            <option value="DESC">{{ trans('laravel-filemanager::lfm.sort-direction-desc') }}</option>
                        </select>

                    </form>
                </div>

                <div class="col-sm-10 col-xs-12" id="main">
                    <div id="alerts"></div>
                    <div id="content"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aia-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('laravel-filemanager::lfm.title-upload') }}</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ route('unisharp.lfm.upload') }}" role='form' id='uploadForm' name='uploadForm'
                          method='post' enctype='multipart/form-data' class="dropzone">
                        <div class="form-group" id="attachment">

                            <div class="controls text-center">
                                <div class="input-group" style="width: 100%">
                                    <a class="btn btn-primary"
                                       id="upload-button">{{ trans('laravel-filemanager::lfm.message-choose') }}</a>
                                </div>
                            </div>
                        </div>
                        <input type='hidden' name='working_dir' id='working_dir'>
                        <input type='hidden' name='type' id='type' value='{{ request("type") }}'>
                        <input type='hidden' name='_token' value='{{csrf_token()}}'>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{{ trans('laravel-filemanager::lfm.btn-close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="lfm-loader">
        <img src="{{ asset('images/loader-32.gif') }}" alt="Loading..." style="width: 24px;height: 24px;" />
    </div>

@endsection

<!-- page script -->
@section('javascript')

    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="{{ asset('vendor/laravel-filemanager/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('vendor/laravel-filemanager/js/cropper.min.js') }}"></script>
    <script src="{{ asset('vendor/laravel-filemanager/js/dropzone.min.js') }}"></script>
    <script>
        let route_prefix = "{{ url('/') }}";
        let lfm_route = "{{ url(config('lfm.url_prefix', config('lfm.prefix'))) }}";
        let lang = {!! json_encode(trans('laravel-filemanager::lfm')) !!};
    </script>
    <script src="{{ asset('vendor/laravel-filemanager/js/script.js') }}"></script>
    <script>

        Dropzone.options.uploadForm = {
            paramName: "upload[]", // The name that will be used to transfer the file
            uploadMultiple: false,
            parallelUploads: 5,
            clickable: '#upload-button',
            dictDefaultMessage: 'Or drop files here to upload',
            init: function () {
                let _this = this; // For the closure
                this.on('success', function (file, response) {
                    if (response == 'OK') {
                        refreshFoldersAndItems('OK');
                    } else {
                        this.defaultOptions.error(file, response.join('\n'));
                    }
                });
            },
            acceptedFiles: "{{ lcfirst(str_singular(request('type') ?: '')) == 'image' ? implode(',', config('lfm.valid_image_mimetypes')) : implode(',', config('lfm.valid_file_mimetypes')) }}",
            maxFilesize: ({{ lcfirst(str_singular(request('type') ?: '')) == 'image' ? config('lfm.max_image_size') : config('lfm.max_file_size') }} / 1000)
        }
    </script>

    <script>





        {{--let route_prefix = "{{ url(config('lfm.url_prefix', config('lfm.prefix'))) }}";--}}

        {{--(function ($) {--}}

        {{--    $.fn.filemanager = function (type, options) {--}}
        {{--        type = type || 'file';--}}

        {{--        this.on('click', function (e) {--}}
        {{--            let route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';--}}
        {{--            localStorage.setItem('target_input', $(this).data('input'));--}}
        {{--            localStorage.setItem('target_preview', $(this).data('preview'));--}}
        {{--            window.open(route_prefix + '?type=' + type, 'FileManager', 'width=900,height=600');--}}
        {{--            window.SetUrl = function (url, file_path) {--}}
        {{--                // set the value of the desired input to image url--}}
        {{--                let target_input = $('#' + localStorage.getItem('target_input'));--}}
        {{--                target_input.val(file_path).trigger('change');--}}

        {{--                // set or change the preview image src--}}
        {{--                let target_preview = $('#' + localStorage.getItem('target_preview'));--}}
        {{--                target_preview.attr('src', url).trigger('change');--}}
        {{--            };--}}
        {{--            return false;--}}
        {{--        });--}}
        {{--    }--}}

        {{--})(jQuery);--}}

        {{--$('#lfm').filemanager('image', {prefix: route_prefix});--}}

    </script>

@endsection
