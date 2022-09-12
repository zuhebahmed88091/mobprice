@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/innolytic/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/innolytic/css/frontstyle.css') }}">
@endsection

<div class="product_list_single_page">
    @if(count($mobiles) == 0)
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="panel-body text-center">
                        <h4>No Mobiles Available!</h4>
                    </div>
                </div>
            </div>
        </div>
    @else

        <ul class="product_list prdct-grid-list clearfix">
            <!-- Start list item 1 -->
            @foreach($mobiles as $mobile)
                <li class="product-item clearfix card product-item-other">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-md-2">
                            @if (stripos($mobile->status, 'coming') !== false)
                                <div class="status-tag upcoming">Upcoming</div>
                            @elseif (stripos($mobile->status, 'rumored') !== false)
                                <div class="status-tag rumoured">Rumored</div>
                            @endif
                            <div class="compair_div_parent d-flex flex-column justify-content-center w-100">
                                <div class="product-item-img-wrapper">
                                    <a href="{{route('mobiledetail', $mobile->id)}}">
                                        <img class="product-item-img"
                                             src="{{ $mobile->featured_image }}?no-cache={{ time() }}"
                                             alt="">
                                    </a>
                                </div>

                                <div class="product-compare list-page pt-3">
                                    <a href="{{route('compare',['c1'=>$mobile->id])}}">
                                        <div class="compare-btn">
                                            <i class="fas fa-balance-scale-left"></i>
                                            <span>Compare</span>
                                        </div>
                                    </a>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-10">
                            <div class="product-item-details">
                                <div class="row">
                                    <div class="col-md-8 mt-4 mt-md-0">
                                        @if($mobile->expert_score !=0 )
                                            <div class="product-item-score">
                                                <span class="product-item-scr-val">
                                                    <div class="mkCharts" data-percent="{{ number_format($mobile->expert_score) }}" data-size="35" data-stroke="3" data-color="#0E4BB5"></div>
                                                </span><br>
                                                Expert Score
                                            </div>
                                        @endif
                                        <a class="product-item-name"
                                           href="{{route('mobiledetail', $mobile->id)}}">{{$mobile->title}}</a>
                                        <div class="product-item-rating-info clearfix">
                                            <div class="rating-wrapper">
                                                <div class="rating-star">
                                                    @if($mobile->avg_rating != null)
                                                        @for( $i=1; $i<=5; $i++)
                                                            @if($i<=$mobile->avg_rating)
                                                                <i class="fa fa-star"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div
                                            class="product-item-price main-wrpr-cols1 text-left text-md-right mt-4 mt-md-0">
                                            <span class="product-item-price-value">{{$mobile->price}}</span>
                                            @if( !empty($mobile->regional_prices))
                                                <div class="product-item-price-more">
                                                    <a href="{{route('mobiledetail',$mobile->id .'#price-section-wrap')}}">
                                                        See more prices
                                                        <i class="fa fa-angle-right" style="font-size: 10px;"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="product-item-spcific-info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="product-item-spcific-wrapper">
                                                @if(!empty($mobile->chipset))
                                                    <li class="product-item-spcification spec-cpu ">
                                                        <div class="icn">
                                                            <i class="fas fa-microchip center"></i>
                                                        </div>
                                                        {!! $mobile->chipset !!}
                                                    </li>
                                                @endif

                                                <li class="product-item-spcification spec-ram ">
                                                    <div class="icn">
                                                        <i class="fas fa-memory "></i>
                                                    </div>
                                                    {{ implode('/', $mobile->rams->pluck('title')->toArray()) }} RAM
                                                </li>
                                                <li class="product-item-spcification spec-int-storage">
                                                    <div class="icn">
                                                        <i class="fas fa-sd-card "></i>
                                                    </div>
                                                    {{ implode('/', $mobile->mobileStorage->pluck('title')->toArray()) }}
                                                    storage
                                                </li>
                                                <li class="product-item-spcification spec-battery">
                                                    <div class="icn">
                                                        <i class="fa fa-battery-three-quarters "></i>
                                                    </div>
                                                    {{$mobile->battery_type}}
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="product-item-spcific-wrapper">
                                                @if(is_string($mobile->cameras))

                                                <li class="product-item-spcification spec-camera">
                                                    <div class="icn">
                                                        <i class="fa fa-camera-retro"></i>
                                                    </div>
                                                    {{ $mobile->rear_camera }}<br>
                                                    {{ $mobile->front_camera }}
                                                </li>
                                                @endif
                                                @if($mobile->size == null)
                                                <li class="product-item-spcification spec-screen">
                                                    <div class="icn">
                                                        <i class="fas fa-mobile-alt"></i>
                                                    </div>
                                                    {{ $mobile->resolution }}
                                                </li>
                                                @else
                                                <li class="product-item-spcification spec-screen">
                                                    <div class="icn">
                                                        <i class="fas fa-mobile-alt"></i>
                                                    </div>
                                                    {{ $mobile->size }}, {{ $mobile->resolution }}
                                                </li>
                                                @endif
                                                <li class="product-item-spcification spec-sim">
                                                    <div class="icn">
                                                        <i class="fas fa-sim-card"></i>
                                                    </div>
                                                    {{ \App\Helpers\ScrapHelper::cleanFirstBracketContent($mobile->sim) }}
                                                </li>
                                                @if($mobile->os != null )
                                                <li class="product-item-spcification spec-os">
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
                                <div class="product-item-details-link">
                                    <a href="{{route('mobiledetail', $mobile->id)}}">See Full Specifications</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

    @endif
</div>


@if ($mobiles->total() > 0)
    <div class="row pgnt">
        <div class="col-sm-4" style="margin: 30px 0;">
            Showing {{ $mobiles->firstItem() }} to {{ $mobiles->lastItem() }} of {{ $mobiles->total() }} entries
        </div>
        <div class="col-sm-8 text-right">
            {{ $mobiles->links('pagination.default') }}
        </div>
    </div>
@endif

<script>
    if (navigator.userAgent.indexOf("Firefox") != -1)
{
    $(document).ready(function () {
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
    });
} else {
    $(document).ready(function () {
        function createCircleChart(percent, color, size, stroke) {
            let svg = `<svg class="mkc_circle-chart" viewbox="0 0 36 36" width="${size}" height="${size}" xmlns="http://www.w3.org/2000/svg">
                <path class="mkc_circle-bg" stroke="#eeeeee" stroke-width="${stroke * 0.5}" fill="none" d="M18 2.0845
                    a 15.9155 15.9155 0 0 1 0 31.831
                    a 15.9155 15.9155 0 0 1 0 -31.831"/>
                <path class="mkc_circle" stroke="${color}" stroke-width="${stroke}" stroke-dasharray="${percent},100" stroke-linecap="round" fill="none"
                    d="M18 2.0845
                    a 15.9155 15.9155 0 0 1 0 31.831
                    a 15.9155 15.9155 0 0 1 0 -31.831" />
                <text class="mkc_info" x="48%" y="51%" alignment-baseline="central" text-anchor="middle" font-size="14">${percent}</text>
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
    });
}
    // $(document).ready(function () {
    //     function createCircleChart(percent, color, size, stroke) {
    //         let svg = `<svg class="mkc_circle-chart" viewbox="0 0 36 36" width="${size}" height="${size}" xmlns="http://www.w3.org/2000/svg">
    //             <path class="mkc_circle-bg" stroke="#eeeeee" stroke-width="${stroke * 0.5}" fill="none" d="M18 2.0845
    //                 a 15.9155 15.9155 0 0 1 0 31.831
    //                 a 15.9155 15.9155 0 0 1 0 -31.831"/>
    //             <path class="mkc_circle" stroke="${color}" stroke-width="${stroke}" stroke-dasharray="${percent},100" stroke-linecap="round" fill="none"
    //                 d="M18 2.0845
    //                 a 15.9155 15.9155 0 0 1 0 31.831
    //                 a 15.9155 15.9155 0 0 1 0 -31.831" />
    //             <text class="mkc_info" x="49%" y="53%" alignment-baseline="central" text-anchor="middle" font-size="14">${percent}</text>
    //         </svg>`;
    //         return svg;
    //         }

    //         let charts = document.getElementsByClassName('mkCharts');

    //         for(let i=0;i<charts.length;i++) {
    //             let chart = charts[i];
    //             let percent = chart.dataset.percent;
    //             let color = ('color' in chart.dataset) ? chart.dataset.color : "#2F4F4F";
    //             let size = ('size' in chart.dataset) ? chart.dataset.size : "100";
    //             let stroke = ('stroke' in chart.dataset) ? chart.dataset.stroke : "1";
    //             charts[i].innerHTML = createCircleChart(percent, color, size, stroke);
    //         }
    // });
</script>


