@extends('layouts.front_end_base')

@section('css')
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

    <div class="outer-wrap section-content-wrap mobile-details-section">
        <div class="content-wrap-inner">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="container-area box-panel">
                            <div class="page-title">
                                <div class="page-info-breadcrumbs">
                                    <a href="{{ route('newmobile') }}">Mobile Phones</a>
                                    ›
                                    <a href="{{ route('mobiledetail', $mobile->id) }}">{{  $mobile->title }}</a>
                                </div>
                                <div class="page-info-update-date">
                                @if( !is_null($mobile->updated_at))
                                    Update On:
                                    <span title="">{{ $mobile->updated_at }}</span>
                                @endif
                                </div>
                            </div>
                            <div>
                                <div class="section-details-title-container" style="display: flex; flex-direction: row">
                                    <div class="heading-bar" style="display: flex; align-items:center">
                                        <h1 class="section-details-title">{{  $mobile->title }}</h1>
                                    </div>
                                    {{-- <span class="details-title-sub">
                                    (
                                   <span class="available-sizes-item">{{  implode('/', $mobile->mobileStorage()->pluck('title')->toArray()) }}</span>
                                   )</span> --}}

                                    <div class="section-details-toolbar">
                                        <div class="tools-bar-rating" style="display: flex; flex-direction:row; align-items:center">
                                            @if(!empty($mobile->expert_score))
                                                <span class="toolsbar-item toolbar-expert">
                                                    <div class="rating" style="display: flex; flex-direction:row; align-items:center">
                                                        <div class="rating-bar" style="padding-bottom:3px">
                                                            <div class="mkCharts" data-percent="{{ number_format($mobile->expert_score) }}" data-size="35" data-stroke="3" data-color="#0E4BB5"></div>
                                                        </div>
                                                        <div class="pl-2">Expert Score</div>
                                                    </div>

                                                </span>
                                            @endif
                                            @if( !empty( $mobile->avg_rating))
                                                <a href="#review-segment">
                                                    <span class="toolsbar-item toolbar-rating">
                                                        <span class="detail-toolbar-score"><i class="fa fa-star fa-sm"></i>{{  number_format($mobile->avg_rating, 2) }}</span>
                                                        @if (!empty($countTotalRating))
                                                            <span>{{ $countTotalRating }}</span> Rating
                                                        @endif
                                                    </span>
                                                </a>
                                            @endif
                                            <div class="text-bar">
                                                @if (!empty( $mobile->official_link))
                                                <a class="toolsbar-item"
                                                   href="{{ !empty($mobile->official_link)? $mobile->official_link : '#'  }}"
                                                   target="_blank">Official Website</a>
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="section-details-wrapper">
                                    <span class="section-details-content">
                                    <span class="section-details-body">
                                        <p>The <strong>{{ $mobile->title }}</strong> comes with a <strong>{{ $mobile->size }}</strong> Display that bears a resolution of {{ $mobile->resolution }}. The phone retains the <strong>{{ $mobile->build }}</strong> body construction. The smartphone comes with <strong>{{ $mobile->os }}</strong> that runs on <strong>{{ str_replace('<br>', ' or ', $mobile->chipset) }}</strong> processor paired with <strong>{{ $mobile->internal }}</strong> and storage. <strong>{{ \App\Helpers\ScrapHelper::cleanFirstBracketContent($mobile->sim) }}</strong> support is provided. For imaging, the phone offers <strong>{{ $mobile->mc_numbers }}</strong> rear cameras. The front houses <strong>{{ $mobile->sc_numbers }}</strong> selfie camera.</p>
                                    </span>
                                    </span>
                                </div>

                                <div class="sctn product-details row m-0">
                                    <div class="product-details-left col-md-5">
                                        <div class="row d-flex flex-column-reverse flex-md-row mb-3">
                                            <div class="product-image-thum col-md-3 pb-2">
                                                @foreach($mobile->initialPreviewImages as $image)
                                                    <div class="image-thum-item active">
                                                        <img class="product-details_tumn_img"
                                                             src="{{ $image }}" alt="">
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="product-details-img-wrapper col-md-8">
                                                <img id="featured" class="product-details-img"
                                                     src="{{ $mobile->featured_image }}?no-cache={{ time() }}">
                                            </div>
                                        </div>


                                    </div>
                                    <div class="product-details-right col-md-7">
                                        <div class="product-details-container">
                                            <div class="compare-div">
                                                <div class="compare-product compare-product-div">
                                                    <a href="{{ route('compare', ['c1'=>$mobile->id]) }}">
                                                        <div class="compare-btn">
                                                            <i class="fas fa-balance-scale-left"></i>
                                                            <span>Compare</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="product-detail-specifications">
                                                <h3 class="specifications-header">
                                                    Key Specifications
                                                </h3>
                                                <ul class="specifications-list">
                                                 @if(!empty($mobile->chipset))
                                                    <li class="specifications-item">
                                                        <div class="icn">
                                                            <i class="fas fa-microchip center"></i>
                                                        </div>
                                                        {!! $mobile->chipset !!}
                                                    </li>
                                                 @endif
                                                    <li class="specifications-item">
                                                        <div class="icn">
                                                            <i class="fas fa-memory"></i>
                                                        </div>
                                                        {{ implode('/', $mobile->rams()->pluck('title')->toArray()) }}
                                                        RAM
                                                    </li>
                                                    <li class="specifications-item">
                                                        <div class="icn">
                                                            <i class="fas fa-sd-card"></i>
                                                        </div>
                                                        {{ implode('/', $mobile->mobileStorage()->pluck('title')->toArray()) }}
                                                        storage
                                                    </li>

                                                    <li class="specifications-item">
                                                        <div class="icn">
                                                            <i class="fa fa-battery-three-quarters "></i>
                                                        </div>
                                                        {{ $mobile->battery_type }}
                                                    </li>
                                                @if(!empty($mobile->rear_camera))
                                                    <li class="specifications-item">
                                                        <div class="icn">
                                                            <i class="fa fa-camera-retro"></i>
                                                        </div>
                                                        {{ $mobile->rear_camera }}<br>
                                                        {{ $mobile->front_camera }}
                                                    </li>
                                                @endif
                                                @if(!empty($mobile->size))
                                                    <li class="specifications-item">
                                                        <div class="icn">
                                                            <i class="fas fa-mobile-alt"></i>
                                                        </div>
                                                        {{ $mobile->size }}, {{ $mobile->resolution }}
                                                    </li>
                                                @endif
                                                    <li class="specifications-item">
                                                        <div class="icn">
                                                            <i class="fas fa-sim-card"></i>
                                                        </div>
                                                        {{ \App\Helpers\ScrapHelper::cleanFirstBracketContent($mobile->sim) }}
                                                    </li>
                                                @if(!empty($mobile->os))
                                                    <li class="specifications-item spec-os">
                                                        <div class="icn">
                                                            <i class="fab fa-android"></i>
                                                        </div>
                                                        {{ $mobile->os }}
                                                    </li>
                                                @endif
                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="algn_wrpr clearfix">
                                </div> --}}
                            </div>
                            <div class="product-details-navs">
                                <ul>
                                    @if (!empty($mobileRegions))<a href="#price-section-wrap"><li> Prices</li></a>@endif
                                    <a href="#full-specifications"><li>Specification</li></a>
                                    <a href="#review-segment"><li> Reviews ({{ $countTotalRating }})</li></a>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

                @if (!empty($mobileRegions))
                    <div class="home-section-box" id="price-section-wrap">
                        <div class="row">
                            <div class="col box-section-wrapper">
                                <div class="box-section-container">
                                    <div class="section-title-container row">
                                        <div class="col-12">
                                            <h3 class="section-main-title">
                                                {{ $mobile->title }} Prices
                                            </h3>
                                        </div>
                                    </div>

                                    <div class="mt-3"></div>

                                    <div class="pl-3 pr-3">
                                        @foreach ($mobileRegions as $region)
                                            <div>
                                                <h5 class="region-title">{{ $region->title }}</h5>
                                            </div>

                                            <table class="table table-bordered table-sm table-prices">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th style="width: 40%">Store</th>
                                                    <th style="width: 30%">Variation</th>
                                                    <th style="width: 30%">Price</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($region->prices as $price)
                                                    <tr>
                                                        <td style="width: 40%">{{ $price->store }}</td>
                                                        <td style="width: 30%">{{ $price->variation }}</td>
                                                        <td style="width: 30%">{{ $region->symbol }} {{ $price->price }}</td>
                                                    </tr>

                                                @endforeach

                                                </tbody>
                                            </table>
                                        @endforeach
                                        <tr>
                                            <td colspan="2">
                                                <div class="text-center pb-2">
                                                    Disclaimer. We can not guarantee that the price on this page is 100%
                                                    correct. <a href="{{route('priceDisclaimer') }}" class="card-link">Read more</a>
                                                </div>
                                            </td>
                                        </tr>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div id="full-specifications" class="home-top-panel box-panel mb-4">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-section-container">
                                <div class="section-title-container row">
                                    <div class="col-12">
                                        <h3 class="section-main-title">
                                            Full Specifications
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="table-responsive pl-3 pr-3 pb-3 bg-white">
                        <table class="table table-bordered table-compare table-spec">
                            <tbody>
                            <tr>
                                <th colspan="2">
                                    General
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">Availability</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->availability }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Released</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->status }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Operating System</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->os }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Dimensions</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->dimensions }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Weight</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->weight }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">SIM</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->sim }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Network</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->technology }}
                                </td>
                            </tr>

                            <tr>
                                <th colspan="2">
                                    Display
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">Screen Size</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->size }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Display Type</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->display_type }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Resolution</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->resolution }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Pixel Density</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->px_density }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Protection</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->protection }}
                                </td>
                            </tr>

                            <tr id="segment-body">
                                <th colspan="2">
                                    Design
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">Build Material</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->build }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Colors</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->colors }}
                                </td>
                            </tr>

                            <!-- Performance -->
                            <tr>
                                <th colspan="2">
                                    Processor
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">Chipset</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->chipset }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">CPU</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->cpu }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">GPU</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->gpu }}
                                </td>
                            </tr>

                            <!-- Storage -->
                            <tr>
                                <th colspan="2">
                                    Memory
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">Storage & RAM</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->internal }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Card slot</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->card_slot }}
                                </td>
                            </tr>

                            <!--Main Camera-->
                            <tr id="segment-camera">
                                <th colspan="2">
                                    Main Camera
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">Number of Cameras</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->mc_numbers }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Resolution</th>
                                <td class="align-middle">
                                    {!! optional($mobile)->mc_resolutions !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Features</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->mc_features }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Video Recording</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->mc_video }}
                                </td>
                            </tr>

                            <!-- Front Camera -->
                            <tr>
                                <th colspan="2">
                                    Selfie Camera
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">Number of Cameras</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->sc_numbers }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Resolution</th>
                                <td class="align-middle">
                                    {!! optional($mobile)->sc_resolutions !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Features</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->sc_features }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Video Recording</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->sc_video }}
                                </td>
                            </tr>

                            <!-- Multimedia -->
                            <tr>
                                <th colspan="2">
                                    Sound
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">loudspeaker</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->loudspeaker }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Audio Jack</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->jack_3_5mm }}
                                </td>
                            </tr>

                            <!-- communications -->
                            <tr>
                                <th colspan="2">
                                    Communications
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">Wi-Fi</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->wlan }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Bluetooth</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->bluetooth }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">GPS</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->gps }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">NFC</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->nfc }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Radio</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->radio }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">USB</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->usb }}
                                </td>
                            </tr>

                            <!--Battery-->
                            <tr>
                                <th colspan="2">
                                    Battery
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">Type</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->battery_type }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Charging</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->charging }}
                                </td>
                            </tr>

                            <!-- Price List -->
                            <tr id="segment-price">
                                <th colspan="2">
                                    Miscellaneous
                                </th>
                            </tr>
                            <tr>
                                <th class="align-middle">Sensors</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->sensors }}
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle">Price</th>
                                <td class="align-middle">
                                    {{ optional($mobile)->price }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
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

                <div class="home-section-box" id="review-segment">
                    <div class="row">
                        <div class="col box-section-wrapper">
                            <div class="box-section-container">
                                <div class="section-title-container row">
                                    <div class="col-12">
                                        <h3 class="section-main-title">User Reviews</h3>
                                    </div>
                                </div>

                                <div class="reviews-wrap">
                                    <div class="row">
                                        <div class="col-md-3 pb-sm-2" style="border-right: solid 1px #ebebeb;">
                                            <div class="reviews-right-box">
                                                <div class="user-rating">
                                                    <span class="ovr-rating">OVERALL RATING</span>
                                                    <span class="rating-point">{{ $mobile->avg_rating }}</span>
                                                    <span class="out-point">/5</span>
                                                    <span class="ovr-rating-desc">
                                                        BASED ON {{ $countTotalRating }} RATING(S)
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 pb-sm-3" style="border-right: solid 1px #ebebeb;">
                                            <div class="rating-box">
                                                <span class="ovr-rating">OVERALL RATING</span>
                                                <ul class="mtr-box">
                                                    <li data-star="5" class="star_rating_filter1">
                                                        <span class="star-label">5 stars</span>
                                                        <div class="mtr-br">
                                                            <span
                                                                title="{{ $countFiveRatings }} User Review(s) with 5 star rating "
                                                                class="mtr_prc"
                                                                style="width:{{ $countTotalRating == 0 ? 0 : ($countFiveRatings/ $countTotalRating)*100 }}%">
                                                            </span>
                                                        </div>
                                                        <span style="float: left;margin-left:4px;margin-top: -12px;">
                                                            {{ $countFiveRatings }}
                                                        </span>
                                                    </li>
                                                    <li data-star="5" class="star_rating_filter1">
                                                        <span class="star-label">4 stars</span>
                                                        <div class="mtr-br">
                                                            <span
                                                                title="{{ $countFourRatings }} User Review(s) with 5 star rating "
                                                                class="mtr_prc"
                                                                style="width:{{ $countTotalRating == 0 ? 0 : ($countFourRatings/ $countTotalRating)*100 }}%">
                                                            </span>
                                                        </div>
                                                        <span style="float: left;margin-left:4px;margin-top: -12px;">
                                                            {{ $countFourRatings }}
                                                        </span>
                                                    </li>
                                                    <li data-star="5" class="star_rating_filter1">
                                                        <span class="star-label">3 stars</span>
                                                        <div class="mtr-br">
                                                            <span
                                                                title="{{ $countThreeRatings }} User Review(s) with 5 star rating "
                                                                class="mtr_prc"
                                                                style="width:{{ $countTotalRating == 0 ? 0 : ($countThreeRatings/ $countTotalRating)*100 }}%">
                                                            </span>
                                                        </div>
                                                        <span style="float: left;margin-left:4px;margin-top: -12px;">
                                                            {{ $countThreeRatings }}
                                                        </span>
                                                    </li>
                                                    <li data-star="5" class="star_rating_filter1">
                                                        <span class="star-label">2 stars</span>
                                                        <div class="mtr-br">
                                                            <span
                                                                title="{{ $countTwoRatings }} User Review(s) with 5 star rating "
                                                                class="mtr_prc"
                                                                style="width:{{ $countTotalRating == 0 ? 0 : ($countTwoRatings/ $countTotalRating)*100 }}%">
                                                            </span>
                                                        </div>
                                                        <span style="float: left;margin-left:4px;margin-top: -12px;">
                                                            {{ $countTwoRatings }}
                                                        </span>
                                                    </li>
                                                    <li data-star="5" class="star_rating_filter1">
                                                        <span class="star-label">1 stars</span>
                                                        <div class="mtr-br">
                                                            <span
                                                                title="{{ $countOneRatings }} User Review(s) with 5 star rating "
                                                                class="mtr_prc"
                                                                style="width:{{ $countTotalRating == 0 ? 0 : $countOneRatings/ $countTotalRating*100 }}%">
                                                            </span>
                                                        </div>
                                                        <span style="float: left;margin-left:4px;margin-top: -12px;">
                                                            {{ $countOneRatings }}
                                                        </span>
                                                    </li>
                                                </ul>
                                                <span
                                                    class="ovr-rating-desc">{{ $countTotalRating }}  USER REVIEW(S)</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 pb-sm-4">
                                            <div class="write-review-box h-100">
                                                @if(Auth::user())
                                                    <div
                                                        class="h-100 w-100 d-flex justify-content-center align-items-center">
                                                        <span class="ovr-rating"
                                                              style="font-size:20px; color:#0F50C2;">
                                                            {{ optional($userReview)->rating ? 'Your Review: '. optional($userReview)->rating : 'Review not submited yet' }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="ovr-rating">SHARE YOUR THOUGHTS</span>
                                                    <a href="{{ url('/login'. '?review-id=' . $mobile->id) }}">
                                                        <span class="btn-review target_link" style="margin-bottom: 5px">
                                                            WRITE A REVIEW
                                                        </span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if(empty($userReview))
                                    @if(Auth::user())
                                        <div class="pl-4 pr-4 pb-4" id="reviewBox">
                                            <h3 class="section-main-title">Write a Review on {{ $mobile->title }}</h3>
                                            <form method="POST" action="{{ route('review.store', $mobile->id) }}"
                                                  id="review-summary-form"
                                                  name="review-summary-form" accept-charset="UTF-8">
                                                {{ csrf_field() }}
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="form-group {{ $errors->has('review_summary') ? 'has-error' : '' }}">
                                                            <label for="review_summary">Summary <span
                                                                    class="text-danger">*</span></label>
                                                            <textarea class="form-control" id="review_summary"
                                                                      name="review_summary"
                                                                      placeholder="Detailed review (300 characters)"
                                                                      rows="5"
                                                                      cols="60" maxlength="300" required=""
                                                                      aria-required="true"></textarea>
                                                            {!! $errors->first('review_summary', '<p class="help-block">:message</p>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="form-group {{ $errors->has('rating') ? 'has-error' : '' }}">
                                                            <label for="rating">Rating <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="rate" style="width: 150px;">
                                                                <input type="radio" id="star5" name="rating"
                                                                       value="5"/>
                                                                <label for="star5" title="5 stars">5 stars</label>
                                                                <input type="radio" id="star4" name="rating"
                                                                       value="4"/>
                                                                <label for="star4" title="4 stars">4 stars</label>
                                                                <input type="radio" id="star3" name="rating"
                                                                       value="3"/>
                                                                <label for="star3" title="3 stars">3 stars</label>
                                                                <input type="radio" id="star2" name="rating"
                                                                       value="2"/>
                                                                <label for="star2" title="2 stars">2 stars</label>
                                                                <input class="form-control" type="radio" id="star1"
                                                                       name="rating" value="1"/>
                                                                <label for="star1" title="1 star">1 star</label>

                                                            </div>
                                                            <span id="rating-error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 m-0 p-0">
                                                    <button type="submit"
                                                            class="btn btn-success btn-flat btn-block">Submit Your
                                                        Review
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                @endif

                                @if($countTotalRating > 0)
                                    <div class="comment-section mb-3">
                                        <div class="container">
                                            <div class="review">
                                                <h3 class="section-main-title">User Opinions and Reviews</h3>
                                                <div class="comment-section">
                                                    @foreach($ratings as $rating)
                                                        <div class="media media-review mb-1">
                                                            <div class="media-user">
                                                                <span>{{ strtoupper(substr(optional($rating->user)->name, 0, 1)) }}</span>
                                                            </div>
                                                            <div class="media-body">
                                                                <div class="M-flex">
                                                                    <h2 class="title">
                                                                        <span> {{ ucfirst(optional($rating->user)->name) }} </span>
                                                                        {{ $rating->created_at }}
                                                                    </h2>
                                                                    <div class="rating-row">
                                                                        <ul>
                                                                            @for ($i = 1; $i <= 5; $i++)
                                                                                @if ($i <= $rating->rating)
                                                                                    <li class="">
                                                                                        <i class="fa fa-star"></i>
                                                                                    </li>
                                                                                @else
                                                                                    <li class="">
                                                                                        <i class="far fa-star"></i>
                                                                                    </li>
                                                                                @endif
                                                                            @endfor
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="description">
                                                                    {!! $rating->review_summary !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            $('.product-details_tumn_img').click(function () {
                $('.image-thum-item').removeClass('active');
                $(this).parent().addClass('active');
                $('#featured').attr('src', $(this).attr('src').replace('thumb', 'large'));
                $('#description').html($(this).attr('alt'));
            });
            $("#review-summary-form").on('submit', function (e) {
                e.preventDefault();
                let rating = $("input[name=rating]:checked").val();
                let summary = $('#review_summary');

                if (!rating) {
                    $('#rating-error').addClass('text-danger').html('Please select your rating')
                }
                if (rating && summary) {
                    this.submit();
                }
            });
            if (navigator.userAgent.indexOf("Firefox") != -1) {
                function createCircleChart(percent, color, size, stroke) {
            let svg = `<svg class="mkc_circle-chart" viewbox="0 0 36 36" width="${size}" height="${size}" xmlns="http://www.w3.org/2000/svg">
                <path class="mkc_circle-bg" stroke="#eeeeee" stroke-width="${stroke * 0.5}" fill="none" d="M18 2.0845
                    a 15.9155 15.9155 0 0 1 0 31.831
                    a 15.9155 15.9155 0 0 1 0 -31.831"/>
                <path class="mkc_circle" stroke="${color}" stroke-width="${stroke}" stroke-dasharray="${percent},100" stroke-linecap="round" fill="none"
                    d="M18 2.0845
                    a 15.9155 15.9155 0 0 1 0 31.831
                    a 15.9155 15.9155 0 0 1 0 -31.831" />
                <text class="mkc_info" x="17px" y="23px" alignment-baseline="central" text-anchor="middle" font-size="14">${percent}</text>
            </svg>`;
            return svg;
            }

            let charts = document.getElementsByClassName('mkCharts');

            for(let i=0;i<charts.length;i++) {
                let chart = charts[i];
                let percent = chart.dataset.percent;
                let color = ('color' in chart.dataset) ? chart.dataset.color : "#2F4F4F";
                let size = ('size' in chart.dataset) ? chart.dataset.size : "100";
                let stroke = ('stroke' in chart.dataset) ? chart.dataset.stroke : "1";
                charts[i].innerHTML = createCircleChart(percent, color, size, stroke);
            }
            } else {
                function createCircleChart(percent, color, size, stroke) {
            let svg = `<svg class="mkc_circle-chart" viewbox="0 0 36 36" width="${size}" height="${size}" xmlns="http://www.w3.org/2000/svg">
                <path class="mkc_circle-bg" stroke="#eeeeee" stroke-width="${stroke * 0.5}" fill="none" d="M18 2.0845
                    a 15.9155 15.9155 0 0 1 0 31.831
                    a 15.9155 15.9155 0 0 1 0 -31.831"/>
                <path class="mkc_circle" stroke="${color}" stroke-width="${stroke}" stroke-dasharray="${percent},100" stroke-linecap="round" fill="none"
                    d="M18 2.0845
                    a 15.9155 15.9155 0 0 1 0 31.831
                    a 15.9155 15.9155 0 0 1 0 -31.831" />
                <text class="mkc_info" x="49%" y="53%" alignment-baseline="central" text-anchor="middle" font-size="14">${percent}</text>
            </svg>`;
            return svg;
            }

            let charts = document.getElementsByClassName('mkCharts');

            for(let i=0;i<charts.length;i++) {
                let chart = charts[i];
                let percent = chart.dataset.percent;
                let color = ('color' in chart.dataset) ? chart.dataset.color : "#2F4F4F";
                let size = ('size' in chart.dataset) ? chart.dataset.size : "100";
                let stroke = ('stroke' in chart.dataset) ? chart.dataset.stroke : "1";
                charts[i].innerHTML = createCircleChart(percent, color, size, stroke);
            }
            }

        });

    </script>
@endsection
