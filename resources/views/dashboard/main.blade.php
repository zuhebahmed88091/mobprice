@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
<h1>Dashboard</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
</ol>
@endsection

@section('content')

@if (App\Helpers\CommonHelper::isCapable('dashboard.changeReportTime'))
<div class="row">
    <div class="col-xs-12">
        <div class="box" style="border-top: 1px solid #d2d6de;">

            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12">

                        <form id="formReportTime" method="POST" class="form-inline pull-right">
                            <div class="form-group">
                                <label for="report-time" class="control-label">Report Time</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control date-range-picker" name="report-time" type="text"
                                        style="min-width: 220px;" id="report-time">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="compare-with" class="control-label">Compare With</label>
                                <div class="input-group date">
                                    <div class="input-group-addon bg-gray">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control date-range-picker" name="compare-with" type="text"
                                        style="min-width: 220px;" disabled id="compare-with">
                                </div>
                            </div>
                            <button type="button" id="btnSearch" class="btn btn-primary">
                                Go
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Info boxes -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <a href="{{route('mobiles.index')}}" class="info-link">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-institution"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Mobiles</span>
                    <span class="info-box-number">{{ $totalMobiles }}</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <a href="{{ route('tickets.index') }}" class="info-link">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-ticket"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Availables</span>
                    <span class="info-box-number">{{ $totalAvailableMobiles }}</span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <a href="#" class="info-link">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-sitemap"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Upcomings</span>
                    <span class="info-box-number">{{ $totalComingSoonMobiles }}</span>
                </div>
            </div>
        </a>
    </div>

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <a href="{{ route('testimonials.index') }}" class="info-link">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-quote-left"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Rumored </span>
                    <span class="info-box-number">{{ $totalRumoredMobiles }}</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <a href="{{ route('testimonials.index') }}" class="info-link">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-quote-left"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Discontinued </span>
                    <span class="info-box-number">{{ $totalDiscontinuedMobiles }}</span>
                </div>
            </div>
        </a>
    </div>
</div>

<div id="dashboardContent" style="margin-top: 5px;"></div>

@endsection

<!-- page script -->
@section('javascript')
<script src="{{ asset('chart.js/Chart.js') }}"></script>
<script src="{{ asset('vendor/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('vendor/morris.js/morris.min.js') }}"></script>
<script>
    $(function () {
            let start = moment().subtract(defaultDashboardTime, 'days');
            let end = moment();
            let startDate = start.format('YYYY-MM-DD');
            let endDate = end.format('YYYY-MM-DD');
            let previousStartDate = '';
            let previousEndDate = '';
            let dateRangeObj = $('#report-time');
            let compareWithObj = $('#compare-with');

            let calculatePreviousPeriod = function () {
                let date1 = moment(startDate, 'YYYY-MM-DD');
                let date2 = moment(endDate, 'YYYY-MM-DD');
                let diffDays = date2.diff(date1, 'days');

                previousStartDate = moment(startDate, 'YYYY-MM-DD').subtract(diffDays, 'days');
                compareWithObj.val(previousStartDate.format(dateFormat) + ' - ' + date1.format(dateFormat));

                previousStartDate = previousStartDate.format('YYYY-MM-DD');
                previousEndDate = startDate;
            };

            let dateRangeOptions = {
                opens: 'left',
                showDropdowns: false,
                linkedCalendars: false,
                ranges: {
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Last 365 Days': [moment().subtract(365, 'days'), moment()],
                    'This Year': [moment().startOf('year'), moment()],
                },
                locale: {
                    format: dateFormat
                },
                startDate: start,
                endDate: end,
                minDays: 10,
                dateLimit: {
                    months: 12
                },
                dateLimitMin: {
                    days: 6
                }
            };

            // daterangepicker for purchase date
            dateRangeObj.daterangepicker(dateRangeOptions);
            dateRangeObj.on('apply.daterangepicker', function (ev, picker) {
                startDate = picker.startDate.format('YYYY-MM-DD');
                endDate = picker.endDate.format('YYYY-MM-DD');

                // Calculate and set previous period
                calculatePreviousPeriod();
            });

            let ajaxRequest = function () {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('dashboard.index') }}',
                    data: {
                        'startDate': startDate,
                        'endDate': endDate,
                        'previousStartDate': previousStartDate,
                        'previousEndDate': previousEndDate
                    },
                    dataType: "html",
                    beforeSend: function () {
                        if (loaderImageHtml) {
                            $('#dashboardContent').html(loaderImageHtml).fadeIn(50);
                        }
                    },
                    success: function (htmlData) {
                        $('#dashboardContent').html(htmlData);
                    },
                    error: function (jsonObj) {
                        if (jsonObj.status === 422) {
                            alertify.error(jsonObj.responseJSON.message);
                        }
                    }
                });
            };

            calculatePreviousPeriod();

            ajaxRequest();

            $('#btnSearch').click(function () {
                ajaxRequest();
            });
        });
</script>

@endsection
