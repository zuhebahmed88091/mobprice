@extends('layouts.app')

@section('content-header')
    <h1>Contact Us</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Contact Us</a></li>
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

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">

                    @if(count($contactUses) == 0)
                        <div class="panel-body text-center">
                            <h4>No Contact Us message Available!</h4>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>SL.</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th style="min-width:100px;" class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($contactUses as $contactUs)
                                    <tr>
                                        <td></td>
                                        <td>{{ $contactUs->full_name }}</td>
                                        <td>{{ $contactUs->email }}</td>
                                        <td>{{ $contactUs->subject }}</td>
                                        <td>
                                            @if ($contactUs->status == 'Not Seen')
                                                <span class="label label-danger">Not Seen</span>
                                            @elseif ($contactUs->status == 'Viewed')
                                                <span class="label label-warning">Viewed</span>
                                            @elseif ($contactUs->status == 'Replied')
                                                <span class="label label-success">Replied</span>
                                            @endif
                                        </td>
                                        <td>{{ $contactUs->created_at }}</td>
                                        <td class="text-center" style="min-width:100px;">

                                            <form method="POST"
                                                  action="{!! route('contact_us.destroy', $contactUs->id) !!}"
                                                  accept-charset="UTF-8">
                                                <input name="_method" value="DELETE" type="hidden">
                                                {{ csrf_field() }}

                                                @if (App\Helpers\CommonHelper::isCapable('contact_us.show'))
                                                    <a href="{{ route('contact_us.show', $contactUs->id ) }}"
                                                       class="btn btn-xs btn-info" title="Show Contact Us">
                                                        <i aria-hidden="true" class="fa fa-eye"></i>
                                                    </a>
                                                @endif

                                                @if (App\Helpers\CommonHelper::isCapable('contact_us.destroy'))
                                                    <button type="submit" class="btn btn-xs btn-danger"
                                                            title="Delete Contact Us"
                                                            onclick="return confirm('Delete Contact Us?')">
                                                        <i aria-hidden="true" class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

<!-- page script -->
@section('javascript')
    <script>
        $(function () {
            let dataTable = $('#dataTable').DataTable({
                'order': [[5, 'desc']],
                "columnDefs": [
                    {"orderable": false, "targets": -1},
                    {"searchable": false, "orderable": false, "targets": 0}
                ]
            });

            dataTable.on('order.dt search.dt', function () {
                dataTable.column(0, {search: 'applied', order: 'applied'})
                    .nodes()
                    .each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();
        });
    </script>
@endsection
