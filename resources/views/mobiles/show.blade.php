@extends('layouts.app')

@section('css')
    <link href="{{ asset('vertical-product-gallery/stylesheet.css') }}" rel="stylesheet">
    <link href="{{ asset('vertical-product-gallery/fancybox/jquery.fancybox.css') }}" rel="stylesheet">
    <link href="{{ asset('thumbnail-scroller-2.0.3/jquery.mThumbnailScroller.css') }}" rel="stylesheet">
@endsection

@section('content-header')
    <h1>View Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('mobiles.index') }}">
                <i class="fa fa-dashboard"></i> Mobiles
            </a>
        </li>
        <li class="active">Show</li>
    </ol>
@endsection

@section('content')

    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{-- {{ ucfirst(optional($mobile->Brand)->title) }} --}}
                {{ isset($mobile->title) ? $mobile->title : 'Mobile' }}
                @if ($mobile->is_duplicate)
                    <span class="badge bg-red">Duplicate</span>
                @endif
            </h3>

            <div class="box-tools pull-right">
                <form method="POST" action="{!! route('mobiles.destroy', $mobile->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    <a href="{{ route('mobiles.index') }}" class="btn btn-sm btn-info"
                       title="Show All Mobile">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    @if($mobile->revision == -1)
                        <a href="{{ route('mobiles.mobile.edit', $mobile->id ) }}"
                           class="btn btn-sm btn-success"
                           title="Publish Mobile">
                            <i class="fa fa-send" aria-hidden="true"></i>
                        </a>
                    @else
                        <a href="{{ route('mobiles.mobile.edit', $mobile->id ) }}"
                           class="btn btn-sm btn-success"
                           title="Send Push Notification">
                            <i class="fa fa-bell-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    <a href="{{ route('mobiles.gallery', $mobile->id ) }}"
                       class="btn btn-sm btn-warning"
                       title="Add Gallery">
                        <i class="fa fa-camera" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('mobiles.mobile.edit', $mobile->id ) }}"
                       class="btn btn-sm btn-primary"
                       title="Edit Mobile">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger" title="Delete Mobile"
                            onclick="return confirm('Click Ok to delete Mobile.?')">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>

                </form>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-lg-4">
                    <table class="table table-bordered">
                        <tr>
                            <td colspan="2" class="text-center">
                                <div class="gallery">
                                    <div class="previews">
                                        @foreach($mobile->initialPreviewImages as $image)
                                        <a href="#"
                                            data-full="{{ $image }}">
                                            <img src="{{ $image }}"/>
                                        </a>
                                        @endforeach
                                    </div>

                                    <div class="full">
                                        <img src="{{ $mobile->featured_image }}?no-cache={{ time() }}" />
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <table class="table table-condensed table-bordered">

                        <tr>
                            <th colspan="2">General</th>
                        </tr>
                        <tr>
                            <td width="30%">Released</td>
                            <td>{{ optional($mobile)->released }}</td>
                        </tr>
                        <tr>
                            <td>Availability</td>
                            <td>{{ optional($mobile)->availability }}</td>
                        </tr>
                        <tr>
                            <td>Operating System</td>
                            <td>{{ optional($mobile)->os }}</td>
                        </tr>
                        <tr>
                            <td>Dimensions</td>
                            <td>{{ optional($mobile)->dimensions }}</td>
                        </tr>
                        <tr>
                            <td>Weight</td>
                            <td>{{ optional($mobile)->weight }}</td>
                        </tr>
                        <tr>
                            <td>SIM</td>
                            <td>{{ optional($mobile)->sim }}</td>
                        </tr>
                        <tr>
                            <td>Network</td>
                            <td>{{ optional($mobile)->technology }}</td>
                        </tr>

                    </table>

                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th colspan="2">Display</th>
                        </tr>
                        <tr>
                            <td width="30%">Screen Size</td>
                            <td>{{ optional($mobile)->size }}</td>
                        </tr>
                        <tr>
                            <td>Display Type</td>
                            <td>{{ optional($mobile)->display_type }}</td>
                        </tr>
                        <tr>
                            <td>Resolution</td>
                            <td>{{ optional($mobile)->resolution }}</td>
                        </tr>
                        <tr>
                            <td>Pixel Density</td>
                            <td>{{ optional($mobile)->px_density }}</td>
                        </tr>
                        <tr>
                            <td>Protection</td>
                            <td>{{ optional($mobile)->protection }}</td>
                        </tr>

                    </table>

                </div>
                <div class="col-lg-4">
                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th colspan="2">Design</th>
                        </tr>
                        <tr>
                            <td width="30%">Build Material</td>
                            <td>{{ optional($mobile)->build }}</td>
                        </tr>
                        <tr>
                            <td>Colors</td>
                            <td>{{ optional($mobile)->colors }}</td>
                        </tr>

                    </table>

                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th colspan="2">Processor</th>
                        </tr>
                        <tr>
                            <td width="30%">Chipset</td>
                            <td>{{ optional($mobile)->chipset }}</td>
                        </tr>
                        <tr>
                            <td>CPU</td>
                            <td>{{ optional($mobile)->cpu }}</td>
                        </tr>
                        <tr>
                            <td>GPU</td>
                            <td>{{ optional($mobile)->gpu }}</td>
                        </tr>

                    </table>

                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th colspan="2">Memory</th>
                        </tr>
                        <tr>
                            <td width="30%">Storage & RAM</td>
                            <td>{{ optional($mobile)->internal }}</td>
                        </tr>
                        <tr>
                            <td>Card slot</td>
                            <td>{{ optional($mobile)->card_slot }}</td>
                        </tr>

                    </table>

                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th colspan="2">Main Camera</th>
                        </tr>
                        <tr>
                            <td width="30%">Number of Cameras</td>
                            <td>{{ optional($mobile)->mc_numbers }}</td>
                        </tr>
                        <tr>
                            <td>Resolution</td>
                            <td>{!! optional($mobile)->mc_resolutions !!}</td>
                        </tr>
                        <tr>
                            <td>Features</td>
                            <td>{{ optional($mobile)->mc_features }}</td>
                        </tr>
                        <tr>
                            <td>Video Recording</td>
                            <td>{{ optional($mobile)->mc_video }}</td>
                        </tr>

                    </table>

                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th colspan="2">Selfie Camera</th>
                        </tr>
                        <tr>
                            <td width="30%">Number of Cameras</td>
                            <td>{{ optional($mobile)->sc_numbers }}</td>
                        </tr>
                        <tr>
                            <td>Resolution</td>
                            <td>{!! optional($mobile)->sc_resolutions !!}</td>
                        </tr>
                        <tr>
                            <td>Features</td>
                            <td>{{ optional($mobile)->sc_features }}</td>
                        </tr>
                        <tr>
                            <td>Video Recording</td>
                            <td>{{ optional($mobile)->sc_video }}</td>
                        </tr>

                    </table>
                </div>

                <div class="col-lg-4">
                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th colspan="2">Sound</th>
                        </tr>
                        <tr>
                            <td width="30%">loudspeaker</td>
                            <td>{{ optional($mobile)->loudspeaker }}</td>
                        </tr>
                        <tr>
                            <td>Audio Jack</td>
                            <td>{{ optional($mobile)->jack_3_5mm }}</td>
                        </tr>

                    </table>

                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th colspan="2">Communications</th>
                        </tr>
                        <tr>
                            <td width="30%">Wi-Fi</td>
                            <td>{{ optional($mobile)->wlan }}</td>
                        </tr>
                        <tr>
                            <td>Bluetooth</td>
                            <td>{{ optional($mobile)->bluetooth }}</td>
                        </tr>
                        <tr>
                            <td>GPS</td>
                            <td>{{ optional($mobile)->gps }}</td>
                        </tr>
                        <tr>
                            <td>NFC</td>
                            <td>{{ optional($mobile)->nfc }}</td>
                        </tr>
                        <tr>
                            <td>Radio</td>
                            <td>{{ optional($mobile)->radio }}</td>
                        </tr>
                        <tr>
                            <td>USB</td>
                            <td>{{ optional($mobile)->usb }}</td>
                        </tr>
                    </table>

                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th colspan="2">Battery</th>
                        </tr>
                        <tr>
                            <td width="30%">Type</td>
                            <td>{{ optional($mobile)->battery_type }}</td>
                        </tr>
                        <tr>
                            <td>Charging</td>
                            <td>{{ optional($mobile)->charging }}</td>
                        </tr>

                    </table>

                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th colspan="2">Miscellaneous</th>
                        </tr>
                        <tr>
                            <td width="30%">Sensors</td>
                            <td>{{ optional($mobile)->sensors }}</td>
                        </tr>
                        <tr>
                            <td>Price</td>
                            <td>{{ optional($mobile)->price }}</td>
                        </tr>

                    </table>
                </div>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="quickEditModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Update Price</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert hidden" id="alertBox"></div>
                            <form id="priceUpdateForm" onSubmit="return updatePrice();" class="form-horizontal">
                                <div class="form-group">
                                    <label for="price" class="col-sm-3 control-label">Price</label>
                                    <div class="col-sm-7">
                                        <div class="input-group">
                                            <span class="input-group-addon">&euro;</span>
                                            <input class="form-control" name="price" type="number" id="price"
                                                   min="-2147483648" max="2147483647"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" id="btnSaveChanges" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection

<!-- page script -->
@section('javascript')
    <!-- fancybox jQuery plugins -->
    <script src="{{ asset('vertical-product-gallery/fancybox/jquery.fancybox.js') }}"></script>

    <!-- thumbnail scroller plugins -->
    <script src="{{ asset('thumbnail-scroller-2.0.3/jquery.mThumbnailScroller.js') }}"></script>

    <script>
        $(document).ready(function () {

            $('a').click(function () {
                let largeImage = $(this).attr('data-full');
                $('.selected').removeClass();
                $(this).addClass('selected');
                $('.full img').hide().attr('src', largeImage).fadeIn();
            });

            $('.full img').on('click', function () {
                let modalImage = $(this).attr('src');
                $.fancybox.open(modalImage);
            });

            $(".previews").mThumbnailScroller({
                theme: "hover-classic",
                axis: "y" //change to "y" for vertical scroller
            });

            $('#btnSaveChanges').click(function () {
                $('#priceUpdateForm').submit();
            });
        });

        function updatePrice() {
            if ($('#priceUpdateForm').valid()) {
                $.ajax({
                    type: 'PUT',
                    url: '{{ url('/mobiles/' . $mobile->id . '/update-price') }}',
                    data: {
                        'price': $('#price').val(),
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function (jsonObj) {
                        $("#alertBox").html(jsonObj.message)
                            .hide()
                            .removeClass('alert-danger hidden')
                            .addClass('alert-success')
                            .fadeIn(1500);

                        setTimeout(function () {
                            location.reload(true);
                        }, 5000);
                    },
                    error: function (jsonObj) {
                        if (jsonObj.status === 422) {
                            $("#alertBox").html(jsonObj.responseJSON.message)
                                .removeClass('alert-success hidden')
                                .addClass('alert-danger');
                        }
                    }
                });
            }

            return false;
        }

    </script>
@endsection
