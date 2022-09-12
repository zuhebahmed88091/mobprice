@extends('layouts.front_end_base')

@section('css')
@endsection

@section('content')
    <div class="outer-wrap section-content-wrap">
        <div class="content-wrap-inner">
            <div class="container">
                <div class="home-top-panel">
                    <div class="row">
                        <div class="col-md-12 col-lg-8 mb-3">
                            <div class="box-left-panel">
                                <div id="mobile" class="left-panel-wrapper">
                                    <div class="lets_find">
                                        Lets Find a Mobile For You!
                                    </div>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="data-panel">
                                                <div class="inner-txt">By Price</div>
                                                <div class="price-bar">
                                                    <div class="price-bar-wrapper">
                                                        <div class="range-slider">
                                                            <input type="text" class="js-range-slider" value=""/>
                                                        </div>
                                                        <div class="extra-controls form-inline row">
                                                            <div class="col-md-12">
                                                                <div class="form-group d-flex">
                                                                    <input type="text"
                                                                           class="col-5 flex-grow-1 js-input-from form-control"
                                                                           value="0"/>
                                                                    <span class="col-2 text-center">to</span>
                                                                    <input type="text"
                                                                           class="col-5 flex-grow-1 js-input-to form-control"
                                                                           value="0"/>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="btn-find-mobiles find_mob" id="findMobiles">
                                                            <span>Find Mobiles <i class="fas fa-chevron-right"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="home-phone-link mb-4">
                                                    <span>
                                                        <a class="" href="{{route('newmobile',['sortBy'=>'popularity'])}}">Popular
                                                        Mobiles <span><i class="fas fa-chevron-right"></i></span></a>
                                                    </span>
                                                    <span>
                                                        <a class="" href="{{route('newmobile',['sortBy'=>'latest'])}}">
                                                            Upcoming Mobiles
                                                            <span><i class="fas fa-chevron-right"></i></span>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="right-brands">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="right-features-panel brands-wrapper">
                                                            <div class="text_right">By Brand</div>
                                                            <ul class="features-items">
                                                                @foreach ($topBrands as $brand)
                                                                    <li>
                                                                        <a href="{{ route('newmobile', ['brand' => $brand->id]) }}">
                                                                            <span
                                                                                class="target_link">{{ $brand->title }}</span>
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>

                                                    </div>
                                                    <div class="col-6">
                                                        <div class="right-features-panel">
                                                            <div class="text_right">By Features</div>
                                                            <ul class="features-items">
                                                                <li>
                                                                    <a href="{{ route('newmobile', ['feature_value' => "5G"]) }}">
                                                                        <span class="target_link">5G Mobile</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ route('newmobile', ['feature_value' => "4000-256000"]) }}">
                                                                        <span class="target_link">4 GB RAM</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ route('newmobile', ['feature_value' => "90-512"]) }}">
                                                                        <span class="target_link">90 Hz & Above Refresh Rate</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ route('newmobile', ['feature_value' => "16-512"]) }}">
                                                                        <span class="target_link">16 MP+ Camera</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ route('newmobile', ['feature_value' => "Android 9.0"]) }}">
                                                                        <span class="target_link">Android pie 9.0</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-4">
                            <div class="box-right-panel">
                                <div class="box-right-panel-content">
                                    <div class="box-right-panel-header">
                                        Popular Comparisons
                                    </div>
                                    <ul class="box-right-panel-wrapper row m-0">
                                        @foreach($popularComparisons as $comparisons)
                                            <div class="col-md-12 comparer-wrap">
                                                <a href="{{route('compare', ['c1'=>$comparisons->mobile1_id, 'c2'=>$comparisons->mobile2_id])}}">
                                                    <div class="d-flex justify-content-between pt-2 pb-2 compare-item">
                                                        <div
                                                            class="compare-left-image d-flex justify-content-center align-items-center">
                                                            <img
                                                            loading="lazy" class="img-responsive"
                                                                 src="{{ $comparisons->mobile1->featured_image }}?no-cache={{ time() }}"
                                                                 alt="" style="width:45px;">
                                                        </div>
                                                        <div
                                                            class="compare-middle-info flex-grow-1 d-flex flex-column justify-content-center align-items-center">
                                                            <p> {{ optional($comparisons->mobile1)->title}}</p>
                                                            <p class="compare-vs">vs</p>
                                                            <p> {{ optional($comparisons->mobile2)->title}}</p>
                                                        </div>
                                                        <div
                                                            class="compare-right-image d-flex justify-content-center align-items-center">
                                                            <img
                                                            loading="lazy" class="img-respnsive"
                                                                 src="{{ $comparisons->mobile2->featured_image  }}?no-cache={{ time() }}"
                                                                 alt="" style="width:45px;">
                                                        </div>

                                                    </div>

                                                </a>
                                            </div>

                                        @endforeach
                                    </ul>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="carousel" style="overflow: hidden;">
                    <div class="home-section-box">
                        <div class="row">
                            <div class="col box-section-wrapper">
                                <div class="box-section-container">
                                    <div class="section-title-container row">
                                        <div class=col-9>
                                            <h3 class="section-main-title">Latest and Upcoming Mobiles</h3>
                                        </div>
                                        <div class="box-section-scroll col-3">
                                            <div class="bbb_viewed_nav bbb_viewed_prev" data-prev="1"><i
                                                    class="fas fa-chevron-left"></i></div>
                                            <div class="bbb_viewed_nav bbb_viewed_next" data-next="1"><i
                                                    class="fas fa-chevron-right"></i></div>
                                        </div>
                                    </div>

                                    <div class="slider-item-container featured-mobile">
                                        <div
                                            class="owl-carousel owl-theme bbb_viewed_slider-1 full_featured_row jcarousel_clip">
                                            @foreach($latestMobiles as $mobile)
                                                <li class="mainContent innerContent jcarousel-item show_li innerContent_mob">
                                                    <a href="{{route('mobiledetail', $mobile->id)}}">
                                                        @if ($mobile->expert_score !=0 )
                                                            <div class="rating_box">
                                                                <div>
                                                                    {{ number_format($mobile->expert_score) }}
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="hm_dil_img item-image">
                                                            <img
                                                            loading="lazy" class="product_image"
                                                                 src="{{ $mobile->featured_image }}?no-cache={{ time() }}"
                                                                 alt="{{ $mobile->title }}">
                                                        </div>

                                                        <div class="title-wrap" style="padding: 15px 10px 10px 5px;">
                                                            <div class="title_name">
                                                                {{ $mobile->title }}
                                                            </div>
                                                            <span class="pro_price">{{ $mobile->price }}</span>
                                                        </div>
                                                    </a>
                                                </li>

                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="home-section-box">
                        <div class="row">
                            <div class="col box-section-wrapper">
                                <div class="box-section-container">
                                    <div class="section-title-container row">
                                        <div class="col-9">
                                            <h3 class="section-main-title">Popular Mobiles</h3>
                                        </div>

                                        <div class="box-section-scroll col-3">
                                            <div class="bbb_viewed_nav bbb_viewed_prev" data-prev="3"><i
                                                    class="fas fa-chevron-left"></i></div>
                                            <div class="bbb_viewed_nav bbb_viewed_next" data-next="3"><i
                                                    class="fas fa-chevron-right"></i></div>
                                        </div>
                                    </div>

                                    <div class="slider-item-container featured-mobile">
                                        <div
                                            class="owl-carousel owl-theme bbb_viewed_slider-3 full_featured_row jcarousel_clip">
                                            @foreach($popularMobiles as $popular)
                                                <li class="mainContent innerContent jcarousel-item show_li innerContent_mob">
                                                    <a href="{{route('mobiledetail', $popular->id)}}">
                                                        <!--box1-->
                                                        @if($popular->expert_score !=0 )
                                                            <div class="rating_box">
                                                                <div>
                                                                    {{ number_format($popular->expert_score)}}
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="hm_dil_img item-image">
                                                            <img loading="lazy"
                                                            class="product_image"
                                                                 src="{{ $popular->featured_image }}?no-cache={{ time() }}"
                                                                 alt="{{ $popular->title }}">
                                                        </div>

                                                        <div class="title-wrap" style="padding: 15px 10px 10px 5px;">
                                                            <div class="title_name">
                                                                {{ $popular->title }}
                                                            </div>
                                                            <span class="pro_price">{{ $popular->price}}</span>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="home-section-box">
                        <div class="row">
                            <div class="col box-section-wrapper">
                                <div class="box-section-container">
                                    <div class="section-title-container row">
                                        <div class="col-9">
                                            <h3 class="section-main-title">Rumored Mobiles</h3>
                                        </div>

                                        <div class="box-section-scroll col-3">
                                            <div class="bbb_viewed_nav bbb_viewed_prev" data-prev="2"><i
                                                    class="fas fa-chevron-left"></i></div>
                                            <div class="bbb_viewed_nav bbb_viewed_next" data-next="2"><i
                                                    class="fas fa-chevron-right"></i></div>
                                        </div>
                                    </div>
                                    <div class="clr"></div>
                                    <div class="slider-item-container featured-mobile">
                                        <div
                                            class="owl-carousel owl-theme bbb_viewed_slider-2 full_featured_row jcarousel_clip">
                                            @foreach($rumoredMobiles as $mobile)
                                                <li class="mainContent innerContent jcarousel-item show_li innerContent_mob">
                                                    <a href="{{route('mobiledetail', $mobile->id)}}">
                                                        @if($mobile->expert_score !=0 )
                                                            <div class="rating_box">
                                                                <div>
                                                                    {{ number_format($mobile->expert_score)}}
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="hm_dil_img item-image">
                                                            <img loading="lazy" class="product_image"
                                                                 src="{{ $mobile->featured_image }}?no-cache={{ time() }}"
                                                                 alt="{{ $mobile->title }}">
                                                        </div>

                                                        <div class="title-wrap" style="padding: 15px 10px 10px 5px;">
                                                            <div class="title_name">
                                                                {{ $mobile->title }}
                                                            </div>
                                                            <span class="pro_price">{{ $mobile->price}}</span>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="home-section-box">
                        <div class="row">
                            <div class="col box-section-wrapper">
                                <div class="box-section-container">
                                    <div class="section-title-container row">
                                        <div class="col-9">
                                            <h3 class="section-main-title">Latest News</h3>
                                        </div>

                                        <div class="box-section-scroll col-3">
                                            <div class="bbb_viewed_nav bbb_viewed_prev" data-prev="4"><i
                                                    class="fas fa-chevron-left"></i></div>
                                            <div class="bbb_viewed_nav bbb_viewed_next" data-next="4"><i
                                                    class="fas fa-chevron-right"></i></div>
                                        </div>
                                    </div>
                                    <div class="clr"></div>
                                    <div class="slider-item-container featured-mobile">
                                        <div
                                            class="owl-carousel owl-theme bbb_viewed_slider-4 full_featured_row jcarousel_clip">

                                            @foreach($allNews as $news)
                                                <li class="mainContent innerContent jcarousel-item show_li innerContent_mob"
                                                    style="height: 260px !important;">
                                                    <a href="{{route('news.view', $news->id)}}">

                                                        <div class="hm_dil_img" style="height: 130px;">
                                                            <img loading="lazy" class="product_image news-image"
                                                                 src="{{ asset('storage/news/' . $news->image)}}"
                                                                 alt="image">
                                                        </div>

                                                        <div class="news-title-wrap"
                                                             style="padding: 15px 10px 10px 5px;">
                                                            <span class="news-title"
                                                                  style="font-weight: 500;font-size: 14px; color: #404040;">
                                                            {{ $news->title}}
                                                            </span>
                                                            <div style="height: 4px;width: 100%;"></div>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="home-section-box">
                        <div class="row" style="overflow: hidden;">
                            <div class="col-md-12">
                                <div class="box-section-container">
                                    <div class="section-title-container row">
                                        <div class="col-8">
                                            <h3 class="section-main-title">Top Brands </h3>
                                        </div>

                                        <div class="col-4" style="margin-top: 16px; text-align:right">
                                            <a href="{{route('all.brands')}}"><span>View All Brands</span> </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="brand-wrap">
                            @foreach($brands as $brand)
                                <a href="{{route('newmobile', ['brand'=>$brand->id])}}">
                                    <div class="brand-item" style="height:105px">
                                        <img loading="lazy" src="{{ asset('storage/brands/'. $brand->image ) }}"
                                             alt="{{$brand->title}}">
                                    </div>
                                </a>
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script>
        // Trigger
        $(function () {
            var maxRate = {{ $currency_rate->rate }};
            let conversion = {{ $max }};
            let $range = $(".js-range-slider"),
                $inputFrom = $(".js-input-from"),
                $inputTo = $(".js-input-to"),
                instance,
                min = 0,
                max = conversion,
                from = 0,
                to = 0;
            $range.ionRangeSlider({
                type: "double",
                min: min,
                max: max,
                from: 0,
                to: conversion,
                prefix: '',
                onStart: updateInputs,
                onChange: updateInputs,
                step: 500,
                prettify_enabled: true,
                values_separator: " - ",
                prettify_separator: ",",
                force_edges: true
            });
            instance = $range.data("ionRangeSlider");

            function updateInputs(data) {
                from = data.from;
                to = data.to;
                $inputFrom.prop("value", from);
                $inputTo.prop("value", to);
            }

            $inputFrom.on("input", function () {
                let val = $(this).prop("value");
                // validate
                if (val < min) {
                    val = min;
                } else if (val > to) {
                    val = to;
                }
                instance.update({
                    from: val
                });
            });
            $inputTo.on("input", function () {
                let val = $(this).prop("value");
                // validate
                if (val < from) {
                    val = from;
                } else if (val > max) {
                    val = max;
                }
                instance.update({
                    to: val
                });
            });

            $('#findMobiles').unbind('click').bind('click', function () {
                let priceFrom = $('.js-input-from').val();
                let priceTo = $('.js-input-to').val();
                window.location.href = '{{ route('newmobile')}}?priceFrom=' + priceFrom + '&priceTo=' + priceTo;
            });
        })
    </script>
@endsection
