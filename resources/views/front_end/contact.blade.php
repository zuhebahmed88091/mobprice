@extends('layouts.front_end_base')

@section('css')
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

        <div class="contact-page outer-wrap section-content-wrap">
            <div class="content-wrap-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="container-area box-panel">
                                <div class="page-title" style="border-bottom: 1px solid #dfe1e8;">
                                   <h3 class="m-0">Contact Us</h3>
                                </div>
                                <div class="section-details-wrapper">
                                        <!-- Contact Section Start -->
                                                @if (config('settings.IS_DISPLAY_CONTACT_US'))
                                                <section id="contact" class="section-padding">
                                                    <div class="container">

                                                        <div class="row contact-form-area wow fadeInUp" data-wow-delay="0.3s">
                                                            <div class="col-lg-7 col-md-12 col-sm-12">
                                                                <div class="contact-block">
                                                                    <form method="POST"  action ="{{route('contact_us_message.store')}}" accept-charset="UTF-8">
                                                                        {{ csrf_field() }}
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <input type="text" class="form-control"
                                                                                        id="full_name" name="full_name" placeholder="Name" aria-label="full_name"
                                                                                        required data-error="Please enter your name">
                                                                                    <div class="help-block with-errors"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <input type="text" placeholder="Email"
                                                                                        id="email" class="form-control" name="email" aria-label="Email"
                                                                                        required data-error="Please enter your email">
                                                                                    <div class="help-block with-errors"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <input type="text" placeholder="Subject"
                                                                                        id="msg_subject" name="subject" class="form-control" aria-label="Subject"
                                                                                        required data-error="Please enter your subject">
                                                                                    <div class="help-block with-errors"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                <textarea class="form-control"
                                                                                        id="message" placeholder="Your Message" aria-label="Message" name="message"
                                                                                        rows="7" data-error="Write your message" required></textarea>
                                                                                    <div class="help-block with-errors"></div>
                                                                                </div>
                                                                                <div class="submit-button text-left">
                                                                                    <button class="btn btn-primary btn-flat"  type="submit">
                                                                                        Send Message
                                                                                    </button>
                                                                                    <div id="msgSubmit" class="h3 text-center hidden"></div>
                                                                                    <div class="clearfix"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-5 col-md-12 col-xs-12">
                                                                <div class="map">
                                                                    <object style="border:0; height: 290px; width: 100%;"
                                                                            data="{{ config('settings.SITE_MAP_MARKER') }}"></object>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>
                                                @endif

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


