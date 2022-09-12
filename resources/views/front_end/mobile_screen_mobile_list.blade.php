@extends('layouts.home')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/innolytic/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/filters.css') }}">
    <style>

        @media screen and (max-width: 1200px){
            #filter-column-left.open {
                right: 30px;
                padding: 5px;
            }

        }
        ul li::before {
            margin: 0 !important;
        }

        .side-nav,
        .nav-menu {
        height: 100%;
        }
        .side-nav .nav-menu {
        list-style: none;
        padding: 40px 0;
        width: 200px;
        background-color: #3498db;
        }
        .side-nav .nav-item {
        position: relative;
        padding: 10px 20px;
        }
        .nav-item.active {
        background-color: #fff;
        box-shadow: 0px -3px rgba(0, 0, 0, 0.2), 0px 3px rgba(0, 0, 0, 0.2);
        }
        .nav-item.active a {
        color: #2980b9;
        }
        .nav-item a {
        text-decoration: none;
        color: #fff;
        }
        .menu-text {
        padding: 0 20px;
        }
        .side-nav .nav-item.active::before {
        content: "";
        position: absolute;
        background-color: transparent;
        bottom: 100%;
        right: 0;
        height: 150%;
        width: 20px;
        border-bottom-right-radius: 25px;
        box-shadow: 0 20px 0 0 #fff;
        }
        .side-nav .nav-item.active::after {
        content: "";
        position: absolute;
        background-color: transparent;
        top: 100%;
        right: 0;
        height: 150%;
        width: 20px;
        border-top-right-radius: 25px;
        box-shadow: 0 -20px 0 0 #fff;
        }

    </style>

@endsection


@section('content')
    <div class="outer-wrap section-content-wrap">
        <div class="content-wrap-inner">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="container-area clearfix">
                            <div class="content-area main-header">

                                <!-- Start heading area -->
                                <div class="heading-area">
                                    <div class="main-heading ">
                                        <div class="d-flex justify-content-between flex-wrap">

                                            <h2 class="main-title">Phone Finder</h2>

                                            <div class="sort-wrap d-flex align-items-center">
                                                <button class="tool-btn" id="lc-toggle" style="margin: 11px;"><i class="material-icons fa fa-filter"></i> Filters</button>
                                                SORT BY
                                                <!--Dropdown area start -->
                                                <select class="sort " name="sort_by" id="sort_by">
                                                    <option value="high-low">Price: High to Low</option>
                                                    <option value="low-high">Price: Low to High</option>
                                                    <option value="popularity" {{  isset($_GET['sortBy']) && $_GET['sortBy']=='popularity' ? 'selected' :''}}>
                                                        Popularity
                                                    </option>
                                                    <option value="newestFirst" {{  isset($_GET['sortBy']) && $_GET['sortBy']=='newestFirst' ? 'selected' :''}}>
                                                        Newest First
                                                    </option>
                                                </select>
                                                <!--Dropdown area End -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Heading area -->
                                <!-- Main Section Content area start -->
                                <div id="sectionContent" class="content-area main-header">
                                    <!-- Product list with pagination area -->
                                    <div class="row">
                                        <div id="filter-column-left" class="col-md-3">
                                            <span class="lc-close"><i class="material-icons fa fa-window-close" aria-hidden="true"></i> </span>
                                            <!--Left side content Start-->
                                            <div class="filter-wrapper-main filters">
                                                <div class="filter-top-section">
                                                    <div class="filter-top-section-header">
                                                        <div class="filter-top-section-title">Filters</div>
                                                        <div class="filter-top-clear">Clear All</div>
                                                    </div>
                                                    <div class="filter-box-items">
                                                        <div class="filter-item" data-groupname="">
                                                            No filters yet
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="">
                                                    <nav class="side-nav">
                                                      <ul class="nav-menu">
                                                        <li class="nav-item"><a href="#"><span class="menu-text">Dashboard</span></a></li>
                                                        <li class="nav-item"><a href="#"><span class="menu-text">Users</span></a></li>
                                                        <li class="nav-item active"><a href="#"></i><span class="menu-text">Posts</span></a></li>
                                                        <li class="nav-item"><a href="#"></i><span class="menu-text">Media</span></a></li>
                                                      </ul>
                                                    </nav>
                                                </div>


                                                <form id="formFilter">
                                                    <div class="fltr_wrpr">
                                                        @foreach($filters as $filter)
                                                            <div class="fltr">
                                                                <div class="fltr_hdr">
                                                                    <span class="fltr_tt1">{{ $filter['tab'] }}</span>
                                                                </div>

                                                                @foreach($filter['sections'] as $section)
                                                                    @if ($section['field'] == 'brands')
                                                                        {{-- <div class="fltr_search">
                                                                            <input class="fltr_search_fld" type="text"
                                                                                   placeholder="search..">
                                                                            <div class="fltr_search_icon fltr_search_icon_backgr">
                                                                            </div>
                                                                        </div> --}}
                                                                    @elseif (!empty($section['header']))
                                                                        <div class="fltr_sub_hdr">
                                                                            <span class="fltr_sub_tt1">{{ $section['header'] }}</span>
                                                                        </div>
                                                                    @endif

                                                                    @if ($section['type'] == 'Checkbox')
                                                                        <div class="filter-option-wrapper">
                                                                            <div class="content">
                                                                                <form action="#">
                                                                                    @foreach($section['options'] as $option)
                                                                                        <div class="fltr_val">
                                                                                            <input type="checkbox"
                                                                                                   id="option_{{ $option['id'] }}"
                                                                                                   name="{{ $section['field'] }}"
                                                                                                   value="{{ $option['value'] }}" {{ isset($_GET['brand']) && $_GET['brand']==$option['id'] && $filter['tab']=='Brands' ? 'checked' : '' }}>
                                                                                            <span
                                                                                                    class="featureName"> {{ $option['name'] }}</span>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    @elseif ($section['type'] == 'Slider')
                                                                        <div class="fltr_prc">
                                                                            <div class="containerr">
                                                                                <div class="roww">
                                                                                    <div class="wrapper">
                                                                                        <div class="range-slider">
                                                                                            <input type="text"
                                                                                                   name="{{ $section['field'] }}"
                                                                                                   id="{{ $section['field'] }}_slider"
                                                                                                   class="js-range-slider"
                                                                                                   value=""
                                                                                                   data-type="double"
                                                                                                   data-min="{{ $section['options']['min'] }}"
                                                                                                   data-max="{{ $section['options']['max'] }}"
                                                                                                   data-from="{{ isset($_GET['priceFrom']) && $filter['tab']=='Price' ? $_GET['priceFrom'] : $section['options']['min'] }}"
                                                                                                   data-to="{{ isset($_GET['priceTo']) && $filter['tab']=='Price' ? $_GET['priceTo'] : $section['options']['max'] }}"
                                                                                                   data-step="{{ $section['options']['step'] }}"
                                                                                            />
                                                                                        </div>
                                                                                        <div class="extra-controls form-inline">
                                                                                            <div class="form-group">
                                                                                                <input name="{{ $section['field'] }}_min"
                                                                                                       type="text"
                                                                                                       id="{{ $section['field'] }}_min"
                                                                                                       class="js-input-from form-control"
                                                                                                       value="{{ $section['options']['min'] }}"/>
                                                                                                <span style="margin: 0 5px">to</span>
                                                                                                <input name="{{ $section['field'] }}_max"
                                                                                                       type="text"
                                                                                                       id="{{ $section['field'] }}_max"
                                                                                                       class="js-input-to form-control"
                                                                                                       value="{{ $section['options']['max'] }}"/>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>

                                                                        <script>
                                                                            // Trigger
                                                                            $(function () {
                                                                                let $range = $("#{{ $section['field'] }}_slider"),
                                                                                    $inputFrom = $("#{{ $section['field'] }}_min"),
                                                                                    $inputTo = $("#{{ $section['field'] }}_max"),
                                                                                    instance,
                                                                                    min = 0,
                                                                                    max = 100000,
                                                                                    from = 0,
                                                                                    to = 0;
                                                                                $range.ionRangeSlider({
                                                                                    prefix: '',
                                                                                    prettify_enabled: true,
                                                                                    prettify_separator: ",",
                                                                                    values_separator: " - ",
                                                                                    onStart: updateInputs,
                                                                                    onChange: updateInputs
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
                                                                                        $(this).val(val);
                                                                                    } else if (val > to) {
                                                                                        val = to;
                                                                                        $(this).val(val);
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
                                                                            });
                                                                        </script>

                                                                    @else
                                                                        <div class="filter-option-wrapper">
                                                                            <div class="content">
                                                                                <form action="#">
                                                                                    @foreach($section['options'] as $option)
                                                                                        <div class="fltr_val">
                                                                                            <input type="radio"
                                                                                                   name="{{ $section['field'] }}"
                                                                                                   value="{{ $option['value'] }}">
                                                                                            <span>{{ $option['name'] }}</span>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </form>
                                                                            </div>
                                                                        </div>

                                                                    @endif
                                                                @endforeach

                                                            </div>
                                                        @endforeach


                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                        <div class="col-md-9 col-12 mobiles-data-list">
                                            <div class="product_list_all">
                                                <!-- Product list item single page -->
                                                @include('front_end.data_list')

                                            </div>
                                        </div>

                                    </div>


                                </div>

                            </div>

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
        $(function () {
            let preparedData = {};
            $('#formFilter input').bind('change', function () {
                let data = {};
                let selectedItem = [];
                $('#formFilter :checked').each(function () {
                    if ($(this).attr('name')) {
                        if (!data[$(this).attr('name')]) {
                            data[$(this).attr('name')] = [];
                        }
                        data[$(this).attr('name')].push($(this).val());
                    }
                    selectedItem.push({
                        'id': $(this).attr('id'),
                        'label': $(this).siblings().text(),
                        'value': $(this).val()
                    });
                });
                // console.log(selectedItem);
                let selectedItemHtml = '';
                for (let item of selectedItem) {
                    selectedItemHtml += `<div class="filter-item" data-option-id="${item.id}">
                        <div class="fltrs_apld_item" data-value="${item.value}">
                            <span class="fltrs_apld_item_label">${item.label}</span>
                            <img class="fltrs_apld_item_cler"
                                src="{{ asset('themes/innolytic/image/cross-grey-small.png') }}">
                        </div>
                       </div>`;
                }
                $('.filter-box-items').html(selectedItemHtml).append(`
                    <script>
                        $(".filter-item").on("click", function (){
                            $(this).hide();
                            let selectedId = $(this).data('option-id');
                            $("#" + selectedId).prop('checked', false);
                            $("#formFilter input").first().trigger("change");
                        });
                    <\/script>
                `);
                $(".filter-top-clear").on("click", function () {
                    selectedItem.length = 0;
                    $("#formFilter input").prop('checked', false).first().trigger("change");
                });
                //console.log(selectedItem);
                preparedData = {};
                for (let key in data) {
                    preparedData[key] = data[key].join('|');
                }
                // preparedData['sort_by'] = $('#sort_by').val();
                getMobiles(1);
            })
            $('body').on('click', '.pagination a', function (e) {
                e.preventDefault();
                $('.product_list_all li').removeClass('active');
                $(this).parent('li').addClass('active');
                let page_no = $(this).attr('href').split('page=')[1];
                getMobiles(page_no);
            });

            function getMobiles(page) {
                preparedData['page'] = page;
                $.ajax({
                    type: "GET",
                    url: '{{ route('newmobile') }}',
                    data: preparedData,
                    dataType: "html",
                    beforeSend: function () {
                        if (loaderImageHtml) {
                            $('.product_list_all').html(loaderImageHtml).fadeIn(50);
                        }
                    },
                    success: function (data) {
                        $('.product_list_all').html(data);
                    },
                    error: function (xhr) {
                        alertify.error(xhr.statusText);
                    }
                })
            }

            // $('#formFilter input').bind('change', function () {
            //
            //     console.log($('#formFilter').serialize());
            //
            // });


            $('.tool-btn , .lc-close').unbind('click', 'body *').bind('click', function(e) {

                if( $('#filter-column-left').hasClass('open')){
                    $('#filter-column-left').removeClass('open');
                } else{
                    $('#filter-column-left').addClass('open');
                }

                if( $('.lc-close').hasClass('close')){
                    $('.lc-close').removeClass('close');
                } else{
                    $('.lc-close').addClass('close');
                }
            });

            $(function() {
                $("li").click(function(e) {
                e.preventDefault();
                $("li").removeClass("active");
                $(this).addClass("active");
                });
		    });

        })
    </script>
@endsection
