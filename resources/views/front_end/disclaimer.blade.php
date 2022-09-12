@extends('layouts.front_end_base')

@section('css')
@endsection

@section('content')
        <div class="disclaimer-page outer-wrap section-content-wrap">
            <div class="content-wrap-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="container-area box-panel">
                                <div class="page-title" style="border-bottom: 1px solid #dfe1e8;">
                                   <h3 class="m-0">Data Disclaimer - definition</h3>
                                </div>
                                <div class="section-details-wrapper">
                                        <span class="section-details-content">
                                            <span class="section-details-body">
                                                <p>We go to extraordinary efforts here at {{ config('settings.SITE_NAME') }} to ensure that the data within our Phone Specifications pages is accurate and up-to-date.

                                                    We have unique relationships with many manufacturers who provide us with product information starting from before an official phone's announcement through to its launch. Where possible, we verify this information during our review process. Additionally, we love phones so we're always checking and double checking our data, often in combination with user feedback from you, our readers.

                                                    Even given our herculean efforts we can not guarantee that the information on our Phone Specifications page is 100% correct.

                                                    If a particular specification is vital to you, we always recommend checking with the phone seller and the best way to start is by visiting their website directly.

                                                    If you think that any information for the current phone is wrong or missing, please contact us here.

                                                    Now for the legalese.

                                                    {{ config('settings.SITE_NAME') }} is not responsible for any errors or omissions, or for the results obtained from the use of this information. All information on this site is provided “as is,” with no guarantee of completeness, accuracy, timeliness or the results obtained from the use of this information.</p>
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


