@extends('layouts.blank_loader')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/innolytic/css/style.css') }}">
@endsection

@section('content')
    <section id="products" class="section-padding">
        <div class="container">
            <div class="section-header text-center">
                <div class="shape wow fadeInDown" data-wow-delay="0.3s"></div>
            </div>
            @include('front_end.privacy_content')
        </div>
    </section>
@endsection
