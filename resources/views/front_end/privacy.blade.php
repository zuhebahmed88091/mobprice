@extends('layouts.front_end_base')

@section('css')
@endsection

@section('content')
    <div class="outer-wrap section-content-wrap privacy-page">
        <div class="content-wrap-inner">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="container-area box-panel">
                            <div class="page-title" style="border-bottom: 1px solid #dfe1e8;">
                                <h3 class="m-0">Privacy Policy</h3>
                            </div>
                            <div class="section-details-wrapper">
                                <div class="section-details-content">
                                    <div class="section-details-body">
                                        @include('front_end.privacy_content')
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


