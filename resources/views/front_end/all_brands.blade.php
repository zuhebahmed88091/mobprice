@extends('layouts.front_end_base')

@section('css')
@endsection

@section('content')
        <div class="all-brands-page outer-wrap section-content-wrap">
            <div class="content-wrap-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="container-area box-panel">
                                <div class="section-title-container row">
                                    <div class="col-12">
                                        <h3 class="section-main-title">
                                            All Brands
                                        </h3>
                                    </div>
                                </div>
                                <div class="section-details-wrapper">
                                    <div class="row equal news-wrap">
                                        @if(count($allBrands) == 0)
                                        <div class="col-md-12">
                                            <div class="box">
                                                <div class="box-body">
                                                    <div class="panel-body text-center">
                                                        <h4>No Brands Available!</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                    <div class="brand-wrap">
                                        @foreach($allBrands as $brand)
                                        <a href="{{route('newmobile', ['brand'=>$brand->id])}}">
                                            <div class="brand-item" style="height:105px">
                                                <img loading="lazy" src="{{ asset('storage/brands/'. $brand->image ) }}" alt="">
                                            </div>
                                        </a>

                                        @endforeach
                                    </div>
                                    @endif
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
@endsection


