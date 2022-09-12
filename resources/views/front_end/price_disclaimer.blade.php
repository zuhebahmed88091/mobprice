@extends('layouts.front_end_base')

@section('css')
@endsection

@section('content')
        <div class="outer-wrap section-content-wrap disclaimer-page">
            <div class="content-wrap-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="container-area box-panel">
                                <div class="page-title" style="border-bottom: 1px solid #dfe1e8;">
                                   <h3 class="m-0">Price Disclaimer</h3>
                                </div>
                                <div class="section-details-wrapper">
                                        <span class="section-details-content">
                                            <span class="section-details-body">
                                                <p>
                                                    We do not falsify or forged any kind of information in {{ config('settings.SITE_NAME') }}.This is outside of our work ethics.We also do not sell any product and we are not affiliated with any seller.

                                                    The information in {{ config('settings.SITE_NAME') }} are collected from various renowned sites and of course official site of related product.As time moves, the price of products are also increased or decreased.So the prices are changed time to time.

                                                    Again the prices of products are different in different regions.So the price of a certain product may vary in term of regions.Most of the prices are the launching price of that particular product.Our main goal is to deliver the approximate idea of products.We will not give any kind of guarantee about the prices of products.

                                                    We are not inclined to take any responsibility if the information of prices are false or wrong.
                                                </p>
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

@section('javascript')
    <script>

    </script>
@endsection


