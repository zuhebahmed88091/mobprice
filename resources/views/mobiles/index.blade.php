@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
    <h1>Mobiles</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Mobiles</a></li>
        <li class="active">Listing</li>
    </ol>
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

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="row">
        <div class="col-xs-12">

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Filter Box</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-sm btn-default" data-widget="collapse">
                            <i class="fa fa-compress"></i>
                        </button>
                        <a href="{{ route('mobiles.index') }}" class="btn btn-info btn-sm" title="Collapse">
                            <i class="fa fa-refresh"></i> Reset
                        </a>
                        @if (App\Helpers\CommonHelper::isCapable('tickets.create'))
                            <a href="{{ route('mobiles.create') }}"
                               class="btn btn-sm btn-success"
                               title="Create New Ticket">
                                <i class="fa fa-plus"></i> Create
                            </a>
                        @endif
                    </div>
                </div>

                <div class="box-body">

                    <form id="formSearch" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label for="mobile_id" class="control-label col-lg-2">Mobile Id</label>
                            <div class="col-lg-4">
                                <input class="form-control" name="mobile_id" id="mobile_id">
                            </div>

                            <label for="title" class="control-label col-lg-2">Title</label>
                            <div class="col-lg-4">
                                <input class="form-control" name="title" id="title">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="control-label col-lg-2">Status</label>
                            <div class="col-lg-4">
                                <select class="form-control select-admin-lte"
                                        id="status" name="status">
                                    <option value="" style="display: none;">-------Select--------</option>
                                    <option value="rumored">Rumored</option>
                                    <option value="coming">Upcoming</option>
                                    <option value="available">Available</option>
                                    <option value="not_published">Not Published</option>
                                    <option value="published_not_in_store">Published (Not Completed)</option>
                                    <option value="published">Published</option>
                                    <option value="discontinued">Discontinued</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            <label for="brand_id" class="control-label col-lg-2">Brands</label>
                            <div class="col-lg-4">
                                <select class="form-control select-admin-lte" id="brand_id" name="brand_id">
                                    <option value="" style="display: none;">-------Select--------</option>
                                    @foreach ($Brands as $key => $Brand)
                                        <option value="{{ $key }}">
                                            {{ ucfirst($Brand) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="date_range" class="control-label col-lg-2">Date</label>
                            <div class="col-lg-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control date-range-picker" name="date_range" type="text"
                                           id="date_range">
                                </div>
                            </div>


                            <label for="announced" class="control-label col-lg-2">Announced</label>
                            <div class="col-lg-3">
                                <input class="form-control" name="announced" id="announced">
                            </div>


                            <div class="col-lg-1 pull-right">
                                <button type="button" id="btnSearch" class="btn btn-primary pull-right">Go
                                </button>
                            </div>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

    {{-- <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">

                    @if(count($mobiles) == 0)
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="{{ route('mobiles.create') }}"
                                   class="btn btn-sm btn-success pull-right"
                                   title="Create New Mobile">
                                    <i aria-hidden="true" class="fa fa-plus"></i> Create
                                </a>
                            </div>
                        </div>

                        <div class="panel-body text-center">
                            <h4>No Mobiles Available.</h4>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="mobileTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-right">Id</th>
                                        <th>Title</th>
                                        <th>Announced</th>
                                        <th>Status</th>
                                        <th class="text-right">Revision</th>
                                        <th class="text-right">Origin</th>
                                        <th class="text-right">Sorting</th>
                                        <th>Created</th>
                                        <th style="width:145px;" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                    @endif

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div> --}}
    <!-- /.row -->


    <div class="data-list"></div>

    <!-- Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="searchResults" id="modalmobilePrices"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Body Weight</h4>
                </div>
                <div class="modal-body mobile-weight-list"></div>
            </div>
        </div>
    </div>

@endsection

<!-- page script -->
@section('javascript')
    <script src="{{ asset('moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('bootstrap-daterangepicker/daterangepicker-custom.js') }}"></script>
    <script>

       function getMobiles(page)
       {
            $.ajax({
                type: "GET",
                url: '{{ route('mobile.mobileList') }}',
                data: {
                    'page': page,
                    'mobileId': $('#mobile_id').val(),
                    'title': $('#title').val(),
                    'status': $('#status').val(),
                    'brandId': $('#brand_id').val(),
                    'announced': $('#announced').val()
                },
                dataType: "html",
                beforeSend: function () {
                    if (loaderImageHtml) {
                        $('.data-list').html(loaderImageHtml).fadeIn(50);
                    }
                },
                success: function (data) {
                    $('.data-list').html(data);
                }
            });
        }
        $(function () {

            let start = moment().subtract(29, 'days');
            let end = moment();
            let startDate = start.format('YYYY-MM-DD');
            let endDate = end.format('YYYY-MM-DD');
            let dateRangeBtnObj = $('#date_range');
            let dataTableUrl = '';

            let loadPriceModal = function (mobileId, mobileTitle) {
                let url = '{{ route('mobile.variationPrices', ':mobileId') }}';
                $.ajax({
                    type: "GET",
                    url: url.replace(':mobileId', mobileId),
                    dataType: "html",
                    beforeSend: function () {
                        $('#modalmobilePrices .modal-title').html('All Price For ' + mobileTitle);
                        //$('#modalmobilePrices').modal('show');
                        if (loaderImageHtml) {
                            $('#modalmobilePrices .modal-body').html(loaderImageHtml).fadeIn(50);
                        }
                    },
                    success: function (data) {

                        $('#modalmobilePrices .modal-body').html(data);

                        let script = document.createElement('script');
                        script.src = '{{ asset('js/common.js') }}';
                        script.type = 'text/javascript';
                        document.getElementsByTagName('body')[0].appendChild(script);
                    },

                })
            }

            let dataTable = $('#mobileTable').DataTable({
                "order": [[7, "desc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'announced', name: 'announced'},
                    {data: 'status', name: 'status'},
                    {data: 'revision', name: 'revision'},
                    {data: 'origin_id', name: 'origin_id'},
                    {data: 'sorting', name: 'sorting'},
                    {data: 'created_at', name: 'created_at'},
                    {
                        data: 'action',
                        name: 'action',
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ],
                "pageLength": 10,
                "pagination": true,
                "columnDefs": [
                    {"orderable": false, "targets": -1},
                    {"orderable": false, "targets": 0}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '<a href="{{ route('mobiles.create') }}" ' +
                        'style="margin-left: 10px" ' +
                        'class="btn btn-sm btn-success" ' +
                        'title="Create New Mobile"> ' +
                        '<i aria-hidden="true" class="fa fa-plus"></i> Create' +
                        '</a>'
                    );
                },
                drawCallback: function () {
                    $('.dataTables_filter label:first').show();

                    $('#formSearch').trigger("reset");
                    $("#brand_id").val('').trigger('change.select2');

                    $('.btn-mobile-cost').click(function () {
                        let mobileId = $(this).data('mobile-id');
                        let mobileTitle = $(this).data('mobile-title');
                        if (mobileId) {
                            loadPriceModal(mobileId, mobileTitle);
                        } else {
                            alertify.error('Please write somethings');
                        }
                    });

                    //$('#formSearch').addClass('hidden');
                }
            });

            dateRangeBtnObj.daterangepicker({
                opens: 'right',
                showDropdowns: true,
                linkedCalendars: false,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    format: dateFormat
                },
                startDate: start,
                endDate: end,
            });

            // Filter the datatable on the datepicker apply event
            dateRangeBtnObj.on('apply.daterangepicker', function (ev, picker) {
                startDate = picker.startDate.format('YYYY-MM-DD');
                endDate = picker.endDate.format('YYYY-MM-DD');
            });

            $('#btnSearch').click(function () {
                getMobiles(1);
            });

            $('body').on('click', '.pagination a', function (e) {
                e.preventDefault();

                $('.data-list li').removeClass('active');
                $(this).parent('li').addClass('active');
                let page_no = $(this).attr('href').split('page=')[1];
                getMobiles(page_no);
            });
            getMobiles(1);
        });
    </script>
@endsection
