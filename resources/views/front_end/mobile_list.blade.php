@extends('layouts.front_end_base')

@section('css')
@endsection


@section('content')
    <script src="{{asset('themes/innolytic/js/jquery-min.js')}}"></script>
    <div class="outer-wrap section-content-wrap filter-page">
        <div class="content-wrap-inner">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="container-area clearfix">
                            <div class="content-area main-header box-panel">
                                <!-- Start heading area -->
                                <div class="heading-area">
                                    <div class="main-heading">
                                        <div class="d-flex justify-content-between flex-wrap">

                                            <h2 class="main-title">Phone Finder</h2>

                                            <div class="sort-wrap d-flex align-items-center">
                                                <button class="tool-btn" id="lc-toggle" style="margin: 11px;"><i
                                                        class="material-icons fa fa-filter"></i> Filters
                                                </button>
                                                SORT BY
                                                <!--Dropdown area start -->
                                                <select class="sort" name="sort_by" id="sort_by">
                                                    <option value="popularity">Popularity</option>
                                                    <option value="high-low">Price: High to Low</option>
                                                    <option value="low-high">Price: Low to High</option>
                                                    <option value="latest" selected>Latest</option>
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
                                            <span class="lc-close">
                                                <i class="material-icons fa fa-window-close"
                                                   aria-hidden="true"></i>
                                            </span>
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

                                                <form id="formFilter">
                                                    <div class="fltr_wrpr">
                                                        @foreach($filters as $filter)
                                                            <div class="fltr">
                                                                <div class="fltr_hdr">
                                                                    @if($filter['tab'] == 'Price')
                                                                        <span class="fltr_tt1">{{ $filter['tab'] }} ({{ $currency_rate->iso_code }}) </span>
                                                                    @elseif ($filter['tab'] == 'Weight')
                                                                        <span
                                                                            class="fltr_tt1">{{ $filter['tab'] }} (gm)</span>
                                                                    @else
                                                                        <span class="fltr_tt1">{{ $filter['tab'] }}</span>
                                                                    @endif
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
                                                                            @if ($section['header'] == 'Battery Capacity')
                                                                                <span
                                                                                    class="fltr_sub_tt1">{{ $section['header'] }} (mah)
                                                                            </span>
                                                                            @else
                                                                                <span
                                                                                    class="fltr_sub_tt1">{{ $section['header'] }}
                                                                            </span>
                                                                            @endif
                                                                        </div>
                                                                    @endif

                                                                    @if ($section['type'] == 'Checkbox')
                                                                        <div class="filter-option-wrapper">
                                                                            <div class="content">
                                                                                <form action="#">
                                                                                    @foreach($section['options'] as $option)
                                                                                        <div class="fltr_val">
                                                                                            <input type="checkbox"
                                                                                                   id="{{ $filter['tab'] =='Brands' ? "option_brand".$option['id'] : "option_".$option['id'] }}"
                                                                                                   name="{{ $section['field'] }}"
                                                                                                   value="{{ $option['value'] }}" {{ isset($_GET['brand']) && $_GET['brand']==$option['id'] && $filter['tab']=='Brands' ? 'checked' : '' }} {{ isset($_GET['feature_value']) && $_GET['feature_value'] === $option['value']  ? 'checked' : '' }}>
                                                                                            <label class="m-0"
                                                                                                   for="{{ $filter['tab'] =='Brands' ? "option_brand".$option['id'] : "option_".$option['id'] }}">
                                                                                                <span
                                                                                                    class="featureName"> {{ $option['name'] }}</span>
                                                                                            </label>
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
                                                                                                   data-max="{{$filter['tab']=='Price' ? $max: $section['options']['max'] }}"
                                                                                                   data-from="{{ isset($_GET['priceFrom']) && $filter['tab']=='Price' ? $_GET['priceFrom'] : $section['options']['min'] }}"
                                                                                                   @if ($filter['tab']=='Price')
                                                                                                   data-to="{{ isset($_GET['priceTo']) && $filter['tab']=='Price' ? $_GET['priceTo'] : $max}}"
                                                                                                   @else
                                                                                                   data-to="{{  $section['options']['max'] }}"
                                                                                                   @endif

                                                                                                   data-step="{{ $section['options']['step'] }}"/>
                                                                                        </div>
                                                                                        <div
                                                                                            class="extra-controls form-inline">
                                                                                            <div class="form-group">
                                                                                                <input
                                                                                                    name="{{ $section['field'] }}_min"
                                                                                                    type="text"
                                                                                                    id="{{ $section['field'] }}_min"
                                                                                                    class="js-input-from form-control"
                                                                                                    value="{{ $section['options']['min'] }}"/>
                                                                                                <span
                                                                                                    style="margin: 0 5px">to</span>
                                                                                                <input
                                                                                                    name="{{ $section['field'] }}_max"
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
                                                                                    max = {{ $section['options']['max'] }},
                                                                                    from = 0,
                                                                                    to = 0;
                                                                                $range.ionRangeSlider({
                                                                                    prefix: '',
                                                                                    prettify_enabled: true,
                                                                                    prettify_separator: ",",
                                                                                    values_separator: " - ",
                                                                                    onStart: updateInputs,
                                                                                    onFinish: updateInputs
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
                                                                                                   id="option_radio_{{ $option['id'] }}"
                                                                                                   name="{{ $section['field'] }}"
                                                                                                   value="{{ $option['value'] }}"
                                                                                                {{ isset($_GET['feature_value']) && $_GET['feature_value'] === $option['value']  ? 'checked' : '' }}>
                                                                                            <label class="m-0"
                                                                                                   for="option_radio_{{ $option['id'] }}">
                                                                                                <span>{{ $option['name'] }}</span>
                                                                                            </label>
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
            let query_url = location.href.split('?');
            let preparedData = {};
            let sortByObj = $('#sort_by');
            let sliders = $('#formFilter input[id$=_slider]');

            @if (isset($_GET['sortBy']) && $_GET['sortBy'] == 'popularity')
            sortByObj.val('popularity');
            getMobiles(1);
            @endif

            $('#formFilter input').not('#formFilter input[type=text]').bind('change', function (event, item) {
                let data = {};
                let selectedItem = [];
                // sliders data
                if (item) {
                    data = item;
                } else {
                    sliders.each(function () {
                        if (!data[$(this).attr('name')]) {
                            data[$(this).attr('name')] = [];
                        }
                        data[$(this).attr('name')].push($(this).data('from'));
                        data[$(this).attr('name')].push($(this).data('to'));
                    });
                }
                //checkboxes and radios data
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
                //console.log(selectedItem);
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
                if ($('.filter-item').length == 0) {
                    $('.filter-box-items').html(`<div class="filter-item" data-groupname="">
                                                            No filters yet
                                                  </div>`);
                    if (query_url.length > 1) {
                        history.replaceState(null, null, query_url[0]);
                    }
                }
                $(".filter-top-clear").on("click", function () {
                    selectedItem.length = 0;
                    $("#formFilter input").prop('checked', false).first().trigger("change");
                });
                //console.log(selectedItem);
                preparedData = {};
                for (let key in data) {
                    preparedData[key] = data[key].join('|');
                }
                  preparedData['sort_by'] = $('#sort_by').val();
                //  console.log(preparedData);
                getMobiles(1);
            });


            //get the textbox/slider changes
            var changeTimer = false;
            $('#formFilter input[type=text]').bind('change', function () {
                let sliderData = {};
                if (changeTimer !== false) clearTimeout(changeTimer);
                changeTimer = setTimeout(function () {
                    sliders.each(function () {
                        if (!sliderData[$(this).attr('name')]) {
                            sliderData[$(this).attr('name')] = [];
                        }
                        sliderData[$(this).attr('name')].push($(this).data('from'));
                        sliderData[$(this).attr('name')].push($(this).data('to'));
                    });
                    $("#formFilter input").first().trigger("change", sliderData);
                    changeTimer = false;
                }, 1000);
            });
            //console.log(sliders);
            //$("#formFilter input").first().trigger("change",'hello');

            $('body').on('click', '.pagination a', function (e) {
                e.preventDefault();
                $('.product_list_all li').removeClass('active');
                $(this).parent('li').addClass('active');
                let page_no = $(this).attr('href').split('page=')[1];
                getMobiles(page_no);
            });
            function getMobiles(page) {
                preparedData['sort_by'] = sortByObj.val();
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

            sortByObj.bind('change', function () {
                preparedData['sort_by'] = sortByObj.val();
                getMobiles(1);
            });

            // $('#formFilter input').bind('change', function () {
            //
            //     console.log($('#formFilter').serialize());
            //
            // });

            $('.tool-btn , .lc-close').unbind('click', 'body *').bind('click', function (e) {

                if ($('#filter-column-left').hasClass('open')) {
                    $('#filter-column-left').removeClass('open');
                } else {
                    $('#filter-column-left').addClass('open');
                }

                if ($('.lc-close').hasClass('close')) {
                    $('.lc-close').removeClass('close');
                } else {
                    $('.lc-close').addClass('close');
                }
            });

             //for get data url from other page
             if (query_url.length > 0) {
                $("#formFilter input").first().trigger("change");
            }
            sortByObj.trigger("change");

        })
    </script>
@endsection
