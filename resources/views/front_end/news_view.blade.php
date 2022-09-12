@extends('layouts.front_end_base')

@section('css')
@endsection

@section('content')
    <div class="outer-wrap section-content-wrap news-view-page">
        <div class="content-wrap-inner">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="container-area box-panel">
                            <div class="section-title-container row">
                                <div class="col-12">
                                    <h3 class="section-main-title">
                                        {{ $news->title}}
                                    </h3>
                                </div>
                            </div>

                            <div class="section-details-wrapper" style="width: 97%;">

                                <div class="news-image text-center">
                                    <img loading="lazy" class="product_image"
                                         src="{{ asset('storage/news/' . $news->image)}}"
                                         style="height:400px;">
                                </div>

                                <span class="section-details-content">
                                    <span class="section-details-body">
                                       {!! $news->description !!}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection


