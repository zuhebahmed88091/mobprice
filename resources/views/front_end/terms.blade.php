@extends(Request::routeIs('mTerms') ? 'layouts.blank_loader' : 'layouts.front_end_base')

@section('css')
@endsection

@section('content')
    <div class="outer-wrap section-content-wrap terms-page">
        <div class="content-wrap-inner">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="container-area box-panel">
                            <div class="section-details-wrapper">
                                <div class="section-details-content">
                                    <div class="section-details-body">
                                        @include('front_end.terms_content')
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


