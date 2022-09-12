<div class="row">

    <div class="col-lg-5 col-md-12 col-xs-12">
        <div class="box box-default">
            <div class="box-header">
                <h3 class="box-title">Tickets</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                            class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                            class="fa fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="box-body no-padding" style="height: 291px;">
                <table class="table table-bordered">
                    <tr>
                        <td style="width: 50%;">
                            <div class="report-box">
                                <div class="left-section">
                                    <h1>{{ $newTickets }}</h1>
                                </div>
                                <div class="right-section">
                                    @if ($ntUpDownPercent >= 0)
                                        <i class="fa fa-arrow-up"></i>
                                    @else
                                        <i class="fa fa-arrow-down"></i>
                                    @endif
                                    <span>{{ $ntUpDownPercent }}%</span>
                                </div>
                                <div class="clearfix"></div>
                                <span>New Tickets</span>
                            </div>
                        </td>
                        <td style="width: 50%;">
                            <div class="report-box">
                                <div class="left-section">
                                    <h1>{{ $newContacts }}</h1>
                                </div>
                                <div class="right-section">
                                    @if ($ncUpDownPercent >= 0)
                                        <i class="fa fa-arrow-up"></i>
                                    @else
                                        <i class="fa fa-arrow-down"></i>
                                    @endif
                                    <span>{{ $ncUpDownPercent }}%</span>
                                </div>
                                <div class="clearfix"></div>
                                <span>New Contacts</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <div class="report-box">
                                <div class="left-section">
                                    <h1>{{ $repliesCount }}</h1>
                                </div>
                                <div class="right-section">
                                    @if ($repliesUpDownPercent >= 0)
                                        <i class="fa fa-arrow-up"></i>
                                    @else
                                        <i class="fa fa-arrow-down"></i>
                                    @endif
                                    <span>{{ $repliesUpDownPercent }}%</span>
                                </div>
                                <div class="clearfix"></div>
                                <span>Total Replies</span>
                            </div>
                        </td>
                        <td style="width: 50%;">
                            <div class="report-box">
                                <div class="left-section">
                                    <h1>{{ $closedTickets }}</h1>
                                    <span>Closed Tickets</span>
                                </div>
                                <div class="right-section">
                                    @if ($ctUpDownPercent >= 0)
                                        <i class="fa fa-arrow-up"></i>
                                    @else
                                        <i class="fa fa-arrow-down"></i>
                                    @endif
                                    <span>{{ $ctUpDownPercent }}%</span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <div class="report-box">
                                <div class="left-section">
                                    <h1>{{ $firstAvgResponseTime }}</h1>
                                </div>
                                <div class="right-section">
                                    @if ($fartUpDownPercent >= 0)
                                        <i class="fa fa-arrow-up"></i>
                                    @else
                                        <i class="fa fa-arrow-down"></i>
                                    @endif
                                    <span>{{ $fartUpDownPercent }}%</span>
                                </div>
                                <div class="clearfix"></div>
                                <span>Avg. First Response Time</span>
                            </div>
                        </td>
                        <td style="width: 50%;">
                            <div class="report-box">
                                <div class="left-section">
                                    <h1>{{ $avgCloseTime }}</h1>
                                </div>
                                <div class="right-section">
                                    @if ($actUpDownPercent >= 0)
                                        <i class="fa fa-arrow-up"></i>
                                    @else
                                        <i class="fa fa-arrow-down"></i>
                                    @endif
                                    <span>{{ $actUpDownPercent }}%</span>
                                </div>
                                <div class="clearfix"></div>
                                <span>Avg. Close Ticket Time</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>

    <div class="col-lg-7 col-md-12 col-xs-12">
        <!-- AREA CHART -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Open Tickets</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                            class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="box-body chart-responsive" style="height: 290px;">
                <div class="chart" id="line-chart" style="height: 240px;"></div>
                <div>
                    <ul class="list-inline" style="margin-left: 10px;">
                        <li><i class="fa fa-circle" style="color: #0F74A8"></i> New</li>
                        <li><i class="fa fa-circle" style="color: #31BB4A"></i> Answered</li>
                        <li><i class="fa fa-circle" style="color: #E7B93C"></i> Not Answered</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-lg-5 col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Tickets by Departments</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                            class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                            class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="box-body" style="height: 290px;">
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart-responsive">
                            <canvas id="pieChart" height="230"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <ul class="chart-legend clearfix">
                            @foreach($departments as $department)
                                <li>
                                    <i class="fa fa-circle-o" style="color: {!! $department->color !!}"></i>
                                    {{ $department->name }} ({{ $department->ticket_count }})
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="box-footer text-center">
                <a href="#" class="uppercase">View All Departments</a>
            </div>

        </div>
    </div>

    <div class="col-lg-7 col-md-12 col-sm-12">
        <!-- BAR CHART -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Busiest Time of Day</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                            class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="box-body" style="height: 290px;">

                @for ($i = 0; $i < 7; $i++)
                    <div class="busy-time-box">
                        <div class="day-column">{{ $dayList[$i] }}</div>
                        <div class="content-column">
                            <div class="row">
                                @for ($j = 0; $j < 12; $j++)
                                    <div class="col-xs-1 padding-2">
                                            <span class="body-cell {{ $busiestTimeChart[$i][$j]['colorClass'] }}">
                                                @if ($busiestTimeChart[$i][$j]['ticketCount'] > 0)
                                                    {{ $busiestTimeChart[$i][$j]['ticketCount'] }}
                                                @endif
                                            </span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endfor

                <div class="busy-time-box">
                    <div class="day-column"></div>
                    <div class="content-column">
                        <div class="row">
                            @for ($j = 0; $j < 12; $j++)
                                <div class="col-xs-1 padding-2">
                                    <span class="footer-cell">{{ $timeList[$j] }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

            </div>
            <div class="box-footer text-center">
                <a href="{{ route('tickets.index') }}" class="uppercase">View All Tickets</a>
            </div>

        </div>
    </div>

</div>

<div class="row">

    <div class="col-lg-5 col-md-12">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Testimonials</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                            class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                            class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="box-body" style="height: 285px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="circle-100 {{ $selectedCircleClass }}">{{ $totalTestimonialCount }}</div>
                    </div>
                </div>

                @foreach($testimonials as $testimonial)
                    <div class="progress-box">
                        <div class="progress-label">{{ $testimonial->name }}</div>
                        <div class="progress progress-xs">
                            <div class="progress-bar progress-bar-striped {{ $testimonial->className }}"
                                 role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                 style="width: {{ $testimonial->percent }}%"></div>
                        </div>
                        <div class="progress-count">({{ $testimonial->count }})</div>
                    </div>
                @endforeach
            </div>
            <div class="box-footer text-center">
                <a href="{{ route('testimonials.index') }}" class="uppercase">View All Testimonials</a>
            </div>

        </div>
    </div>

    <div class="col-md-7">
        <!-- USERS LIST -->
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Latest Members
                </h3>

                <div class="box-tools pull-right">
                    @if ($latestCustomer)
                        <span class="label label-danger">
                                {{ $latestCustomer }} New Members
                            </span>
                    @endif

                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                            class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                            class="fa fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="box-body no-padding">
                <ul class="users-list clearfix">
                    @foreach($customers as $customer)
                        <li>
                            @if ($customer->uploadedFile)
                                <a class="users-list-name" href="{{ route('users.show', $customer->id) }}">
                                    <img
                                        src="{{ asset('storage/profiles/' . optional($customer->uploadedFile)->filename) }}"
                                        alt="{{ $customer->name }}" style="width: 85px;height: 85px;">
                                </a>
                            @else
                                <a class="users-list-name" href="{{ route('users.show', $customer->id) }}">
                                    <img
                                        src="{{ asset('storage/sites/no_avatar.png') }}"
                                        alt="{{ $customer->name }}" style="width: 85px;height: 85px;">
                                </a>
                            @endif
                            <a class="users-list-name" href="{{ route('users.show', $customer->id) }}">
                                {{ $customer->name }}
                            </a>
                            <span class="users-list-date">
                                {{ App\Helpers\CommonHelper::displayTimeFormat($customer->created_at, true) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="box-footer text-center">
                <a href="{{ route('users.index') }}" class="uppercase">View All Users</a>
            </div>
        </div>

    </div>

</div>

<!-- page script -->
<script>
    let pieChartData = JSON.parse('{!! $pieChart !!}');
    let lineChartData = JSON.parse('{!! $lineChartData !!}');
</script>
<script src="{{ asset('js/dashboard.js?noCache=' . date('Ymd')) }}"></script>
