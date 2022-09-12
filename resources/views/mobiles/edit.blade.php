@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('bootstrap-toggle/css/bootstrap-toggle.min.css') }}">
<link href="{{ asset('vertical-product-gallery/stylesheet.css') }}" rel="stylesheet">
<link href="{{ asset('vertical-product-gallery/fancybox/jquery.fancybox.css') }}" rel="stylesheet">
<link href="{{ asset('thumbnail-scroller-2.0.3/jquery.mThumbnailScroller.css') }}" rel="stylesheet">    
@endsection
@section('content-header')
    <h1>Mobile Info</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('mobiles.index') }}">
                <i class="fa fa-dashboard"></i> Mobiles
            </a>
        </li>
        <li class="active">Edit</li>
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

<div class="nav-tabs-custom">
    
    <ul class="nav nav-tabs pull-right">
        <li class="{{ $_GET['tab'] == 'quick-update' ? 'active': '' }}"><a href="#Section5" role="tab" data-toggle="tab"><i aria-hidden="true" class="fa fa-fighter-jet"></i>  Quick Update</a></li>
        <li><a href="#Section3" role="tab" data-toggle="tab"><i class="fa fa-star"></i> User Reviews</a></li>
        <li><a href="#Section2" role="tab" data-toggle="tab"><i class="fa fa-money" aria-hidden="true"></i> Variation Price</a></li>
        <li class="{{ $_GET['tab'] == 'edit' ? 'active': '' }}"><a href="#Section1" role="tab" data-toggle="tab"><i aria-hidden="true" class="fa fa-pencil"></i> Edit</a></li>
        <li class="{{ $_GET['tab'] == 'view' ? 'active': '' }}"><a href="#Section4" role="tab" data-toggle="tab"><i aria-hidden="true" class="fa fa-eye"></i>  View</a></li>
      <li class="pull-left header"> {{ !empty($mobile->title) ? $mobile->title : 'Mobile' }}</li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade {{ $_GET['tab'] == 'edit' ? 'in active': '' }}" id="Section1">

            <form method="POST" class="form-horizontal" action="{{ route('mobiles.update', $mobile->id) }}"
                  id="edit_mobile_form"
                  name="edit_mobile_form">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                <div class="box-body">

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @include ('mobiles.form', ['mobile' => $mobile,])
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                </div>
            </form>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="Section2" style="margin:15px;">
            <div class="row">
                
                <div class="col-md-8">
                    <div class="alert alert-success hidden" id="success-msg-box">
                        <span class="glyphicon glyphicon-ok"></span>
                        <span id="msgText"></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="table-responsive data-table">
                        @include('mobiles.pricees_table', ['variationPrices' => $variationPrices])
                    </div>
                
                </div>
                <div class="col-md-4">
                    <form method="POST" action="{{ route('mobile_prices.store') }}" id="create_mobile_price_form"
                        name="create_mobile_price_form" accept-charset="UTF-8" >
                        {{ csrf_field() }}
                    <input type="hidden" name="mobile_id" value="{{ $mobile->id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('region_id') ? 'has-error' : '' }}">
                                    <label for="region_id">Region</label>
                                    <select class="form-control " id="region_id" name="region_id" required>
                                        <option value="">-----Select-----</option>
                                        @foreach ($regions as $key => $region)
                                        <option value="{{ $key }}" {{ old('region_id', optional($mobilePrice)->region_id) == $key ? 'selected' : '' }}>
                                            {{ $region }}
                                        </option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('region_id', '<p class="help-block">:message</p>') !!}
                                </div>
            
                            </div>
                        </div>
            
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('variation') ? 'has-error' : '' }}">
                                    <label for="variation">Variation</label>
                                    <input class="form-control" name="variation" type="text" id="variation"
                                           value="{{ old('variation') }}" required>
                                    {!! $errors->first('variation', '<p class="help-block">:message</p>') !!}
                                    
                                </div>
            
                            </div>
                        </div>
            
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('default_price') ? 'has-error' : '' }}">
                                    <label for="default_price">Price</label>
                                    <input class="form-control" name="default_price" type="number" id="default_price"
                                        value="{{ old('default_price', optional($mobilePrice)->price) }}" min="-1000000000000000000" max="1000000000000000000" required step="any">
                                    {!! $errors->first('default_price', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
            
                                <div class="form-group {{ $errors->has('usd_price') ? 'has-error' : '' }}">
                                    <label for="usd_price">Usd Price</label>
                                    <input class="form-control" name="usd_price" type="number" id="usd_price"
                                        value="{{ old('usd_price', optional($mobilePrice)->usd_price) }}" min="-1000000000000000000" max="1000000000000000000" required step="any">
                                    {!! $errors->first('usd_price', '<p class="help-block">:message</p>') !!}
                                </div>
            
                            </div> --}}
                        </div>
            
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('store') ? 'has-error' : '' }}">
                                    <label for="store">Store Name</label>
                                    <input class="form-control" name="store" type="text" id="store"
                                           value="{{ old('store', optional($mobilePrice)->store) }}" required>
                                    {!! $errors->first('store', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('affiliate_url') ? 'has-error' : '' }}">
                                    <label for="affiliate_url">Affiliate Url</label>
                                            <textarea class="form-control" name="affiliate_url" cols="50" rows="2" id="affiliate_url">{{ old('affiliate_url', optional($mobilePrice)->affiliate_url) }}</textarea>
                        
                                    {!! $errors->first('affiliate_url', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('priceStatus') ? 'has-error' : '' }}">
                                    <label for="priceStatus">Status</label>
                                    <select class="form-control " id="priceStatus" name="priceStatus" required>
                                        <option value="">-----Select-----</option>
                                        @foreach (['Active' => 'Active', 'Inactive' => 'Inactive'] as $key => $text)
                                            <option value="{{ $key }}" {{ old('priceStatus', optional($mobilePrice)->status) == $key ? 'selected' : '' }}>
                                                {{ $text }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('priceStatus', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
            
                        <div class="row">
                            <div class="col-md-12" style="padding: 18px;">
                                <button type="button" class="btn btn-primary pull-right btn-variation-price-save">Add mobilePrice</button>
                            </div>
                        </div>
            
                    </form>

                </div>


            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="Section3" style="margin:15px;">
            <div class="alert alert-success hidden" id="opinions-msg-box">
                <span class="glyphicon glyphicon-ok"></span>
                <span id="opinionsMsgText"></span>
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @if( count($allUserOpinions)==0)
                <h3 class="text-center">No User Revies available</h3>
            @else
            <div class="row">
                @foreach($allUserOpinions as $opinions)
                    <div class="col-md-6">
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title">
                                    <span> {{ optional($opinions->user)->name}} </span>
                                </h3>
                                <div class="box-tools pull-right">
                                        <span style="color:#ffc107;font-size:20px;margin:0px 3px;"> @for( $i=1; $i<=5; $i++)
                                            @if($i<=$opinions->rating)
                                                <i class="fa fa-star"></i>
                                            @else
                                                <i class="fa fa-star-o"></i>
                                            @endif
                                        @endfor
                                        </span>
                                        <span style="font-size:20px;margin:0px 3px;">
                                            {{$opinions->created_at}}
                                        </span>

                                        <span class="status-div">
                                            <input class="review-status" id="review-status_{{ $opinions->id }}" type="checkbox" value="0"
                                        {{ optional($opinions)->status == "Approved" ? 'checked' : '' }}
                                            data-opinions-id= {{ $opinions->id }}
                                            data-toggle="toggle"
                                            data-on="On"
                                            data-off="Off"
                                            data-onstyle="success"
                                            data-offstyle="danger"
                                            aria-label="status">
                                        </span>
                                </div>
                            </div>
                            <div class="box-body">
                                {!! $opinions->review_summary !!} 
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
                
            @endif
           
        </div>
        <div role="tabpanel" class="tab-pane fade {{ $_GET['tab'] == 'view' ? 'in active': '' }}" id="Section4" style="margin:15px;">
            @include ('mobiles.tab_show', ['mobile' => $mobile,])
            
        </div>
         <div role="tabpanel" class="tab-pane fade {{ $_GET['tab'] == 'quick-update' ? 'in active': '' }}" id="Section5" style="margin:15px;">
            @include ('mobiles.tab_quick_update', ['mobile' => $mobile,])
            
        </div> 

    </div>
    <!-- /.tab-content -->
</div>
    
<a href="{{ route('mobiles.index') }}"
        class="btn btn-md btn-primary" title="Go Back">
         <i aria-hidden="true" class="fa fa-arrow-left"> Go Back</i>
    </a>
@endsection

@section('javascript')
<!-- fancybox jQuery plugins -->
<script src="{{ asset('vertical-product-gallery/fancybox/jquery.fancybox.js') }}"></script>

<!-- thumbnail scroller plugins -->
<script src="{{ asset('thumbnail-scroller-2.0.3/jquery.mThumbnailScroller.js') }}"></script>

<script src="{{ asset('bootstrap-toggle/js/bootstrap-toggle.min.js') }}"></script>
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

            
            
            
            $('.btn-variation-price-save').click(function () {
                let formPriceObj = $('#create_mobile_price_form');
                formPriceObj.validate();
                if (formPriceObj.valid()) {
                    let Id = $(this).data('id');
                    let saveUrl = '{{ route('mobile_prices.store') }}';
                    let data = {
                        'mobile_id': '{{ $mobile->id }}',
                        'region_id': $('#region_id').val(),
                        'variation': $('#variation').val(),
                        'price': $('#default_price').val(),
                        // 'usd_price': $('#usd_price').val(),
                        'store': $('#store').val(),
                        'status': $('#priceStatus').val(),
                        'affiliate_url': $('#affiliate_url').val(),
                        '_token': '{{ csrf_token() }}'
                    };

                    if (Id) {
                        saveUrl = '{{ route('mobile_prices.update', ':Id') }}';
                        saveUrl = saveUrl.replace(':Id', Id);
                        data["_method"] = "PUT";
                    }

                    $.ajax({
                        type: "POST",
                        url: saveUrl,
                        data: data,
                        dataType: "html",
                        beforeSend: function () {
                            if (loaderImageHtml) {
                                $('.data-table').html(loaderImageHtml).fadeIn(50);
                            }
                        },
                        success: function (jsonString) {
                            let jsonObject = JSON.parse(jsonString);
                            if (jsonObject.status === 'OK') {
                                $('#msgText').html(jsonObject.message);
                                $('#success-msg-box')
                                    .removeClass('hidden')
                                    .fadeIn(1500)
                                    .fadeOut(4500);

                                $('.data-table').html(jsonObject.html);

                                $('#create_mobile_price_form').trigger("reset");
                                $("#region_id, #priceStatus").val('').trigger('change.select2');

                                let btnSave = $('.btn-variation-price-save');
                                btnSave.html('Add variation Price');
                                btnSave.data('id', '');
                            } else {
                                $('#success-msg-box').addClass('hidden');
                            }
                        }
                    });
                }
            });

            $(".btn-edit").click(function () {
                let regionObj = $('#region_id');
                let variationObj = $('#variation');
                let priceObj = $('#default_price');
                // let usdPriceObj = $('#usd_price');
                let storeObj = $('#store');
                let affiliateUrlObj = $('#affiliate_url');
                let statusObj = $('#priceStatus');
                let btnSave = $('.btn-variation-price-save');

                let Id = $(this).data('id');
                let regionId = $(this).data('region-id');
                let variation = $(this).data('variation');
                let price = $(this).data('price');
                let usdPrice = $(this).data('usd-price');
                let store = $(this).data('store');
                let status = $(this).data('status');
                let affiliateUrl = $(this).data('affiliate-url');
            
                regionObj.val(regionId).trigger('change');
                priceObj.val(price);
                // usdPriceObj.val(usdPrice);
                variationObj.val(variation);
                storeObj.val(store);
                affiliateUrlObj.val(affiliateUrl);
                statusObj.val(status);
                btnSave.html('Update');
                btnSave.data('id', Id);
            });

            
           
            $('.btn-price-delete').click(function () {
                if(confirm("Delete mobile Price?")) {
                    let url = '{{ route('mobile_prices.destroy', ':Id') }}';
                    let Id = $(this).data('id');
                    if (Id) {
                        $.ajax({
                            type: "POST",
                            url: url.replace(':Id', Id),
                            data: {
                                '_method': 'DELETE',
                                '_token': '{{ csrf_token() }}'
                            },
                            dataType: "html",
                            beforeSend: function () {
                                if (loaderImageHtml) {
                                    $('.data-table').html(loaderImageHtml).fadeIn(50);
                                }
                            },
                            success: function (jsonString) {
                                let jsonObject = JSON.parse(jsonString);
                                if (jsonObject.status === 'OK') {
                                    $('#msgText').html(jsonObject.message);
                                    $('#success-msg-box')
                                        .removeClass('hidden')
                                        .fadeIn(1500)
                                        .fadeOut(4500);

                                    $('.data-table').html(jsonObject.html);
                                } else {
                                    $('#success-msg-box').addClass('hidden');
                                }
                            }
                        });
                    }
                }
            }); 

                $('.review-status').change(function () {
                    var opinionsId = $(this).data('opinions-id');
                    if ($(this).prop("checked") == true) {
                        $('#review-status_' +opinionsId).val(1);
                    } else {
                        $('#review-status_'+opinionsId).val(0);
                    }

                    let params = {
                        'opinionsId': opinionsId,
                        'status': $('#review-status_' +opinionsId).val(),
                        '_token': '{{ csrf_token() }}'
                    }
                    params._method = 'PUT';
                    $.ajax({
                        type: "POST",
                        url: '{{ route('opinions.status.update') }}',
                        data: params,
                        dataType: "html",
                        success: function (jsonString) {
                            let jsonObject = JSON.parse(jsonString);
                                if (jsonObject.status === 'OK') {
                                    $('#opinionsMsgText').html(jsonObject.message);
                                    $('#opinions-msg-box')
                                        .removeClass('hidden')
                                        .fadeIn(1500)
                                        .fadeOut(4500);
                                } else {
                                    $('#opinions-msg-box').addClass('hidden');
                                }
                        }
                    })

                });
        });
    </script>
@endsection


