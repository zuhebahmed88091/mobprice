@extends('layouts.front_end_base')

@section('css')
@endsection

@section('content')
    <div class="container">
        <div class="home-top-panel box-panel mb-4">

            <!-- Summary -->
            <div class="table-responsive">
                <table class="table table-bordered table-compare">
                    <tbody>
                    <tr>
                        <th class="align-middle">
                            <h4>Jump To</h4>
                            <div class="list-group">
                                <a href="#segment-body" class="list-group-item sm-item">
                                    Body <i class="fas fa-chevron-right"></i>
                                </a>
                                <a href="#segment-display" class="list-group-item sm-item">
                                    Display <i class="fas fa-chevron-right"></i>
                                </a>
                                <a href="#segment-camera" class="list-group-item sm-item">
                                    Camera <i class="fas fa-chevron-right"></i>
                                </a>
                                <a href="#segment-price" class="list-group-item sm-item">
                                    Price <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </th>
                        <td>
                            @if (!empty($c1Mobile))
                                <div class="block device_block" id="suggest1">
                                    <div class="device_header">
                                        <span class="cross" data-column="c1">x</span>
                                    </div>
                                    <div class="block block_info" style=" text-align: center;">
                                        <a href="{{route('mobiledetail', optional($c1Mobile)->id) }}">
                                            <img class="mob_img"
                                                 src="{{ $c1Mobile->featured_image }}?no-cache={{ time()  }}"
                                                 alt="{{ optional($c1Mobile)->title  }}">
                                        </a>

                                        <div class="right ttl_search">
                                            {{ optional($c1Mobile)->title }}
                                            <p>
                                                <span class="prices">
                                                    {{ optional($c1Mobile)->price }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @elseif (!empty($s1Mobile))
                                <div class="block device_block compare-column">
                                    <div class="add_search">
                                        <div id="search-area">
                                            <div class="search-box">
                                                <div class="input-field second-wrap typeahead__container">
                                                    <div class="typeahead__query">
                                                        <input id="search-input-compare1" class="typeahead"
                                                               type="text" aria-label="search"
                                                               autocomplete="off" autofocus
                                                               placeholder="Add a mobile"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="block block_info">
                                        <a href="{{route('mobiledetail', optional($s1Mobile)->id) }}">
                                            <img class="mob_img" alt="{{ optional($s1Mobile)->title  }}"
                                                 src="{{ $s1Mobile->featured_image }}?no-cache={{ time()  }}">
                                        </a>

                                        <div class="right ttl_search">
                                            {{ optional($s1Mobile)->title }}
                                            <p>
                                                <span class="prices">
                                                    {{ optional($s1Mobile)->price }}
                                                </span>
                                            </p>
                                        </div>
                                        <a class="btn btn-compare-1 btn-sm"
                                           href="{{route('compare', ['c1'=>$s1Mobile->id, 'c2'=> Request::get('c2'), 'c3'=> Request::get('c3'), 'c4'=> Request::get('c4') ]) }}">
                                            + Compare
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if (!empty($c2Mobile))
                                <div class="block device_block" id="suggest2">
                                    <div class="device_header">
                                        <span class="cross" data-column="c2">x</span>
                                    </div>
                                    <div class="block block_info">
                                        <a href="{{route('mobiledetail', optional($c2Mobile)->id) }}">
                                            <img class="mob_img" alt="{{ optional($c2Mobile)->title  }}"
                                                 src="{{ $c2Mobile->featured_image }}?no-cache={{ time()  }}">
                                        </a>
                                        <div class="right ttl_search">
                                            {{ optional($c2Mobile)->title }}
                                            <p>
                                                <span class="prices">
                                                    {{ optional($c2Mobile)->price }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @elseif (!empty($s2Mobile))
                                <div class="block device_block compare-column">
                                    <div class="add_search">
                                        <div id="search-area">
                                            <div class="search-box">
                                                <div class="input-field second-wrap typeahead__container">
                                                    <div class="typeahead__query">
                                                        <input id="search-input-compare2" class="typeahead"
                                                               type="text" aria-label="search"
                                                               autocomplete="off"
                                                               placeholder="Add a mobile"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="block block_info">
                                        <a href="{{route('mobiledetail', optional($s2Mobile)->id) }}">
                                            <img class="mob_img"
                                                 alt="{{ optional($s2Mobile)->title  }}"
                                                 src="{{ $s2Mobile->featured_image }}?no-cache={{ time()  }}">
                                        </a>
                                        <div class="right ttl_search">
                                            {{ optional($s2Mobile)->title }}
                                            <p>
                                                <span class="prices">
                                                    {{ optional($s2Mobile)->price }}
                                                </span>
                                            </p>
                                        </div>
                                        <a class="btn btn-compare-1 btn-sm"
                                           href="{{route('compare', ['c2'=>$s2Mobile->id, 'c3'=> Request::get('c3'), 'c4'=> Request::get('c4'), 'c1'=> Request::get('c1')]) }}">
                                            + Compare
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if (!empty($c3Mobile))
                                <div class="block device_block" id="suggest3">
                                    <div class="device_header">
                                        <span class="cross" data-column="c3">x</span>
                                    </div>
                                    <div class="block block_info">
                                        <a href="{{route('mobiledetail', optional($c3Mobile)->id) }}">
                                            <img class="mob_img"
                                                 alt="{{ optional($c3Mobile)->title  }}"
                                                 src="{{ $c3Mobile->featured_image }}?no-cache={{ time()  }}">
                                        </a>
                                        <div class="right ttl_search">
                                            {{ optional($c3Mobile)->title  }}
                                            <p>
                                                <span class="prices">
                                                    {{ optional($c3Mobile)->price }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @elseif (!empty($s3Mobile))
                                <div class="block device_block compare-column">
                                    <div class="add_search">
                                        <div id="search-area">
                                            <div class="search-box">
                                                <div class="input-field second-wrap typeahead__container">
                                                    <div class="typeahead__query">
                                                        <input id="search-input-compare3" class="typeahead"
                                                               type="text" aria-label="search"
                                                               autocomplete="off"
                                                               placeholder="Add a mobile"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="block block_info">
                                        <a href="{{route('mobiledetail', optional($s3Mobile)->id) }}">
                                            <img class="mob_img"
                                                 alt="{{ optional($s3Mobile)->title  }}"
                                                 src="{{ $s3Mobile->featured_image }}?no-cache={{ time() }}">
                                        </a>
                                        <div class="right ttl_search">
                                            {{ optional($s3Mobile)->title  }}
                                            <p>
                                                <span class="prices">
                                                    {{ optional($s3Mobile)->price }}
                                                </span>
                                            </p>
                                        </div>

                                        <a class="btn btn-compare-1 btn-sm"
                                           href="{{route('compare', ['c3'=>$s3Mobile->id, 'c4'=> Request::get('c4'), 'c2'=> Request::get('c2'), 'c1'=> Request::get('c1')]) }}">
                                            + Compare
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if (!empty($c4Mobile))
                                <div class="block device_block" id="suggest4">
                                    <div class="device_header">
                                        <span class="cross" data-column="c4">x</span>
                                    </div>
                                    <div class="block block_info">
                                        <a href="{{route('mobiledetail', optional($c4Mobile)->id) }}">
                                            <img class="mob_img" alt="{{ optional($c4Mobile)->title  }}"
                                                 src="{{ $c4Mobile->featured_image }}?no-cache={{ time() }}">
                                        </a>
                                        <div class="right ttl_search">
                                            {{ optional($c4Mobile)->title }}
                                            <p>
                                                <span class="prices">
                                                    {{ optional($c4Mobile)->price }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @elseif (!empty($s4Mobile))
                                <div class="block device_block compare-column">
                                    <div class="add_search">
                                        <div id="search-area">
                                            <div class="search-box last-column">
                                                <div class="input-field second-wrap typeahead__container">
                                                    <div class="typeahead__query">
                                                        <input id="search-input-compare4" class="typeahead"
                                                               type="text" aria-label="search"
                                                               autocomplete="off"
                                                               placeholder="Add a mobile"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="block block_info">

                                        <a href="{{route('mobiledetail', optional($s4Mobile)->id) }}">
                                            <img class="mob_img" alt="{{ optional($s4Mobile)->title  }}"
                                                 src="{{ $s4Mobile->featured_image }}?no-cache={{ time() }}">
                                        </a>

                                        <div class="right ttl_search">
                                            {{ optional($s4Mobile)->title  }}
                                            <p>
                                                <span class="prices">
                                                    {{ optional($s4Mobile)->price }}
                                                </span>
                                            </p>
                                        </div>

                                        <a class="btn btn-compare-1 btn-sm"
                                           href="{{route('compare', ['c4'=>$s4Mobile->id, 'c3'=> Request::get('c3'), 'c2'=> Request::get('c2'), 'c1'=> Request::get('c1')]) }}">
                                            + Compare
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th colspan="5">
                            General
                        </th>
                    </tr>
                    <tr>
                        <th class="align-middle">Availability</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->availability }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->availability }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->availability }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->availability }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Released</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->status }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->status }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->status }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->status }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Operating System</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->os }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->os }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->os }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->os }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Dimensions</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->dimensions }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->dimensions }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->dimensions }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->dimensions }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Weight</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->weight }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->weight }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->weight }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->weight }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">SIM</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->sim }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->sim }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->sim }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->sim }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Network</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->technology }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->technology }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->technology }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->technology }}
                        </td>
                    </tr>

                    <tr>
                        <th id="segment-display" colspan="5">
                            Display
                        </th>
                    </tr>
                    <tr>
                        <th class="align-middle">Screen Size</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->size }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->size }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->size }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->size }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Display Type</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->display_type }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->display_type }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->display_type }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->display_type }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Resolution</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->resolution }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->resolution }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->resolution }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->resolution }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Pixel Density</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->px_density }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->px_density }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->px_density }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->px_density }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Protection</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->protection }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->protection }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->protection }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->protection }}
                        </td>
                    </tr>

                    <tr id="segment-body">
                        <th colspan="5">
                            Design
                        </th>
                    </tr>
                    <tr>
                        <th class="align-middle">Build Material</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->build }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->build }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->build }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->build }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Colors</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->colors }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->colors }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->colors }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->colors }}
                        </td>
                    </tr>


                    <!-- Performance -->
                    <tr>
                        <th colspan="5">
                            Processor
                        </th>
                    </tr>
                    <tr>
                        <th class="align-middle">Chipset</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->chipset }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->chipset }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->chipset }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->chipset }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">CPU</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->cpu }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->cpu }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->cpu }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->cpu }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">GPU</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->gpu }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->gpu }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->gpu }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->gpu }}
                        </td>
                    </tr>

                    <!-- Storage -->
                    <tr>
                        <th colspan="5">
                            Memory
                        </th>
                    </tr>
                    <tr>
                        <th class="align-middle">Storage & RAM</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->internal }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->internal }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->internal }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->internal }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Card slot</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->card_slot }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->card_slot }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->card_slot }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->card_slot }}
                        </td>
                    </tr>

                    <!--Main Camera-->
                    <tr id="segment-camera">
                        <th colspan="5">
                            Main Camera
                        </th>
                    </tr>
                    <tr>
                        <th class="align-middle">Number of Cameras</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->mc_numbers }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->mc_numbers }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->mc_numbers }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->mc_numbers }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Resolution</th>
                        <td class="align-middle">
                            {!! optional($c1Mobile)->rear_camera !!}
                        </td>
                        <td class="align-middle">
                            {!! optional($c2Mobile)->rear_camera !!}
                        </td>
                        <td class="align-middle">
                            {!! optional($c3Mobile)->rear_camera !!}
                        </td>
                        <td class="align-middle">
                            {!! optional($c4Mobile)->rear_camera !!}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Features</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->mc_features }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->mc_features }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->mc_features }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->mc_features }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Video Recording</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->mc_video }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->mc_video }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->mc_video }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->mc_video }}
                        </td>
                    </tr>

                    <!-- Front Camera -->
                    <tr>
                        <th colspan="5">
                            Selfie Camera
                        </th>
                    </tr>
                    <tr>
                        <th class="align-middle">Number of Cameras</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->sc_numbers }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->sc_numbers }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->sc_numbers }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->sc_numbers }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Resolution</th>
                        <td class="align-middle">
                            {!! optional($c1Mobile)->front_camera !!}
                        </td>
                        <td class="align-middle">
                            {!! optional($c2Mobile)->front_camera !!}
                        </td>
                        <td class="align-middle">
                            {!! optional($c3Mobile)->front_camera !!}
                        </td>
                        <td class="align-middle">
                            {!! optional($c4Mobile)->front_camera !!}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Features</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->sc_features }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->sc_features }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->sc_features }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->sc_features }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Video Recording</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->sc_video }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->sc_video }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->sc_video }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->sc_video }}
                        </td>
                    </tr>

                    <!-- Multimedia -->
                    <tr>
                        <th colspan="5">
                            Sound
                        </th>
                    </tr>
                    <tr>
                        <th class="align-middle">loudspeaker</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->loudspeaker }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->loudspeaker }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->loudspeaker }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->loudspeaker }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Audio Jack</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->jack_3_5mm }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->jack_3_5mm }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->jack_3_5mm }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->jack_3_5mm }}
                        </td>
                    </tr>

                    <!-- communications -->
                    <tr>
                        <th colspan="5">
                            Communications
                        </th>
                    </tr>
                    <tr>
                        <th class="align-middle">Wi-Fi</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->wlan }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->wlan }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->wlan }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->wlan }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Bluetooth</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->bluetooth }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->bluetooth }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->bluetooth }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->bluetooth }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">GPS</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->gps }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->gps }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->gps }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->gps }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">NFC</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->nfc }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->nfc }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->nfc }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->nfc }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Radio</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->radio }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->radio }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->radio }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->radio }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">USB</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->usb }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->usb }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->usb }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->usb }}
                        </td>
                    </tr>

                    <!--Battery-->
                    <tr>
                        <th colspan="5">
                            Battery
                        </th>
                    </tr>
                    <tr>
                        <th class="align-middle">Type</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->battery_type }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->battery_type }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->battery_type }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->battery_type }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Charging</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->charging }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->charging }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->charging }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->charging }}
                        </td>
                    </tr>

                    <!-- Price List -->
                    <tr id="segment-price">
                        <th colspan="5">
                            Miscellaneous
                        </th>
                    </tr>
                    <tr>
                        <th class="align-middle">Sensors</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->sensors }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->sensors }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->sensors }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->sensors }}
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle">Price</th>
                        <td class="align-middle">
                            {{ optional($c1Mobile)->price }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c2Mobile)->price }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c3Mobile)->price }}
                        </td>
                        <td class="align-middle">
                            {{ optional($c4Mobile)->price }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <div class="text-center">
                                Disclaimer. We can not guarantee that the information on this page is 100%
                                correct. <a href="{{route('disclaimer') }}" class="card-link">Read more</a>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            let searchInputCompare1Obj = $('#search-input-compare1');
            let searchInputCompare2Obj = $('#search-input-compare2');
            let searchInputCompare3Obj = $('#search-input-compare3');
            let searchInputCompare4Obj = $('#search-input-compare4');
            $.urlParam = function (name) {
                var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                if (results == null) {
                    return null;
                }
                return decodeURI(results[1]) || 0;
            }
            $(".cross").on("click", function () {
                let column = $(this).data('column');
                let url = new URL(location.href);
                if (column === 'c1') {
                    url.searchParams.delete('c1');
                } else if (column === 'c2') {
                    url.searchParams.delete('c2');
                } else if (column === 'c3') {
                    url.searchParams.delete('c3');
                } else if (column === 'c4') {
                    url.searchParams.delete('c4');
                }
                location.href = url;
            });
            searchInputCompare1Obj.typeahead({
                minLength: 1,
                maxItem: 0,
                dynamic: true,
                hint: true,
                template: function (query, item) {
                    return `{!! view('front_end.mobile_list_item1') !!}`;
                },
                emptyTemplate: "No results found for <b>@{{query}}</b>",
                source: {
                    mobiles: {
                        display: ["title"],
                        href: "@{{href}}",
                        templateValue: "@{{title}}",
                        ajax: function (query) {
                            return {
                                type: "GET",
                                url: '{{ route('mobiles.searchList')  }}',
                                path: "data.mobiles",
                                data: {
                                    q: query,
                                    field: 'c1',
                                    c1: $.urlParam('c1'),
                                    c2: $.urlParam('c2'),
                                    c3: $.urlParam('c3'),
                                    c4: $.urlParam('c4')
                                },
                                callback: {
                                    done: function (data, textStatus, jqXHR) {
                                        // Perform operations on received data...
                                        // IMPORTANT: data has to be returned if this callback is used
                                        return data;
                                    },
                                    fail: function (jqXHR, textStatus, errorThrown) {
                                    },
                                    always: function (data, textStatus, jqXHR) {
                                    },
                                    then: function (jqXHR, textStatus) {
                                    }
                                }
                            }
                        }
                    }
                }
            });
            searchInputCompare2Obj.typeahead({
                minLength: 1,
                maxItem: 0,
                dynamic: true,
                hint: true,
                template: function (query, item) {
                    return `{!! view('front_end.mobile_list_item1') !!}`;
                },
                emptyTemplate: "No results found for <b>@{{query}}</b>",
                source: {
                    mobiles: {
                        display: ["title"],
                        href: "@{{href}}",
                        templateValue: "@{{title}}",
                        ajax: function (query) {
                            return {
                                type: "GET",
                                url: '{{ route('mobiles.searchList')  }}',
                                path: "data.mobiles",
                                data: {
                                    q: query,
                                    field: 'c2',
                                    c1: $.urlParam('c1'),
                                    c2: $.urlParam('c2'),
                                    c3: $.urlParam('c3'),
                                    c4: $.urlParam('c4')
                                },
                                callback: {
                                    done: function (data, textStatus, jqXHR) {
                                        // Perform operations on received data...
                                        // IMPORTANT: data has to be returned if this callback is used
                                        //alert(22)
                                        return data;
                                    },
                                    fail: function (jqXHR, textStatus, errorThrown) {
                                    },
                                    always: function (data, textStatus, jqXHR) {
                                    },
                                    then: function (jqXHR, textStatus) {
                                    }
                                }
                            }
                        }
                    }
                }
            });
            searchInputCompare3Obj.typeahead({
                minLength: 1,
                maxItem: 0,
                dynamic: true,
                hint: true,
                template: function (query, item) {
                    return `{!! view('front_end.mobile_list_item1') !!}`;
                },
                emptyTemplate: "No results found for <b>@{{query}}</b>",
                source: {
                    mobiles: {
                        display: ["title"],
                        href: "@{{href}}",
                        templateValue: "@{{title}}",
                        ajax: function (query) {
                            return {
                                type: "GET",
                                url: '{{ route('mobiles.searchList')  }}',
                                path: "data.mobiles",
                                data: {
                                    q: query,
                                    field: 'c3',
                                    c1: $.urlParam('c1'),
                                    c2: $.urlParam('c2'),
                                    c3: $.urlParam('c3'),
                                    c4: $.urlParam('c4')
                                },
                                callback: {
                                    done: function (data, textStatus, jqXHR) {
                                        // Perform operations on received data...
                                        // IMPORTANT: data has to be returned if this callback is used
                                        //alert(33)
                                        return data;
                                    },
                                    fail: function (jqXHR, textStatus, errorThrown) {
                                    },
                                    always: function (data, textStatus, jqXHR) {
                                    },
                                    then: function (jqXHR, textStatus) {
                                    }
                                }
                            }
                        }
                    }
                }
            });
            searchInputCompare4Obj.typeahead({
                minLength: 1,
                maxItem: 0,
                dynamic: true,
                hint: true,
                template: function (query, item) {
                    return `{!! view('front_end.mobile_list_item1') !!}`;
                },
                emptyTemplate: "No results found for <b>@{{query}}</b>",
                source: {
                    mobiles: {
                        display: ["title"],
                        href: "@{{href}}",
                        templateValue: "@{{title}}",
                        ajax: function (query) {
                            return {
                                type: "GET",
                                url: '{{ route('mobiles.searchList')  }}',
                                path: "data.mobiles",
                                data: {
                                    q: query,
                                    field: 'c4',
                                    c1: $.urlParam('c1'),
                                    c2: $.urlParam('c2'),
                                    c3: $.urlParam('c3'),
                                    c4: $.urlParam('c4')
                                },
                                callback: {
                                    done: function (data, textStatus, jqXHR) {
                                        // Perform operations on received data...
                                        // IMPORTANT: data has to be returned if this callback is used
                                        return data;
                                    },
                                    fail: function (jqXHR, textStatus, errorThrown) {
                                    },
                                    always: function (data, textStatus, jqXHR) {
                                    },
                                    then: function (jqXHR, textStatus) {
                                    }
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
