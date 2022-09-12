@extends('layouts.app')

<!-- page css -->
@section('css')
    <link href="{{ asset('bootstrap-fileinput/css/fileinput.css') }}" media="all" rel="stylesheet" type="text/css"/>
@endsection

@section('content-header')
    <h1>Manage Gallery</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('mobiles.index') }}">
                <i class="fa fa-dashboard"></i> Mobiles
            </a>
        </li>
        <li class="active">Gallery</li>
    </ol>
@endsection

@section('content')
    <form enctype="multipart/form-data">
        <input type="hidden" id="csrf_token" name="_token" value="{{ csrf_token() }}">
        <div class="file-loading">
            <input name="file[]" id="kv-explorer" type="file" multiple>
        </div>
    </form>
@endsection

<!-- page script -->
@section('javascript')
    <script src="{{ asset('bootstrap-fileinput/js/sortable.js') }}"></script>
    <script src="{{ asset('bootstrap-fileinput/js/fileinput.js') }}"></script>
    <script src="{{ asset('bootstrap-fileinput/themes/fa/theme.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>

    <script>

        $(document).ready(function () {

            $("#kv-explorer").fileinput({
                theme: 'fa',
                uploadUrl: '{{ url('/admin/gallery/upload') }}',
                uploadExtraData: {
                    'itemId': '{{ $mobile->id }}',
                    '_token': $('#csrf_token').val()
                },
                fileActionSettings: {
                    showUpload: false
                },
                uploadAsync: false,
                minFileCount: 1,
                maxFileCount: 10,
                showRemove: false, // hide remove button
                showUpload: true, // hide upload button
                showCancel: false,
                showClose: false,
                showCaption: false,
                overwriteInitial: false,
                initialPreviewAsData: true,
                initialPreview: {!! json_encode($initialPreview) !!},
                initialPreviewConfig: {!! json_encode($initialPreviewConfig) !!},
                allowedFileExtensions: ['jpg', 'png'],
            }).on('filesorted', function (e, params) {
                let fileStack = [];
                for (let i = 0; i < params.stack.length; i++) {
                    fileStack.push(params.stack[i].caption);
                }

                $.ajax({
                    type: 'POST',
                    url: '{{ url('admin/gallery/sorting') }}',
                    data: {
                        'itemId': '{{ $mobile->id }}',
                        '_token': '{{ csrf_token() }}',
                        'fileStack': fileStack
                    },
                    dataType: "text",
                    success: function (msg) {
                        location.reload(true);
                    }
                });
            });

            $(".btn-move").click(function () {
                $.ajax({
                    type: 'POST',
                    url: '{{ url('/gallery/move') }}',
                    data: {
                        'itemId': '{{ $mobile->id }}',
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: "text",
                    success: function (response) {
                        if (response === '1') {
                            location.href = "{{ url('admin/mobiles/show/' . $mobile->id) }}";
                        } else if (response === '2') {
                            alert('there is no uploaded images in image folder.')
                        }
                    }
                });
            });

            $(".btn-go-back").click(function () {
                
                location.href = "{{ url('admin/mobiles/' . $mobile->id . '/edit?tab=view') }}";
            });

        });
    </script>
@endsection
