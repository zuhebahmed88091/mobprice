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
                                <img src="{{ $image }}" alt="preview"/>
                            </a>
                            @endforeach
                        </div>

                        <div class="full">
                            <img src="{{ $mobile->featured_image }}?no-cache={{ time() }}"/>
                        </div>
                        <div style="display: flex;align-items: flex-end">
                            <div class="button">
                                <a href="{{ route('mobiles.gallery', $mobile->id ) }}"
                                    class="btn btn-sm btn-warning"
                                    title="Add Gallery">
                                     <i class="fa fa-camera" aria-hidden="true"></i>
                                 </a>
                            </div>
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
                <td>{{ optional($mobile)->weight }} ({{ optional($mobile)->gm_weight }})</td>
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
                <td>{{ optional($mobile)->size }} ({{ optional($mobile)->inch_size }})</td>
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
    <div class="col-lg-4" style="padding-top: 20px">
        <a href="{{ optional($mobile)->detail_url }}" target="_blank" rel="noopener noreferrer">View Source</a>
    </div>
</div>
