@extends('layouts.app')

@section('content-header')
    <h1>Testimonial Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('testimonials.index') }}">
                <i class="fa fa-dashboard"></i> Testimonials
            </a>
        </li>
        <li class="active">Details</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Testimonial' }} Full Information
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('testimonials.destroy', $testimonial->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('testimonials.index'))
                        <a href="{{ route('testimonials.index') }}" class="btn btn-sm btn-info"
                           title="Show All Testimonial">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('testimonials.printDetails'))
                        <a href="{{ route('testimonials.printDetails', $testimonial->id) }}"
                           class="btn btn-sm btn-warning"
                           title="Print Details">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('testimonials.create'))
                        <a href="{{ route('testimonials.create') }}" class="btn btn-sm btn-success"
                           title="Create New Testimonial">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('testimonials.edit'))
                        <a href="{{ route('testimonials.edit', $testimonial->id ) }}"
                           class="btn btn-sm btn-primary" title="Edit Testimonial">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('testimonials.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="Delete Testimonial"
                                onclick="return confirm('Delete Testimonial?')">
                            <i aria-hidden="true" class="fa fa-trash"></i>
                        </button>
                    @endif

                </form>

            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-show">
                    <tbody>
                    <tr>
                        <th>Customer</th>
                        <td>{{ optional($testimonial->customer)->name }}</td>
                    </tr>
                    <tr>
                        <th>Rating</th>
                        <td>{{ $testimonial->rating }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $testimonial->status }}</td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td>{{ $testimonial->message }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $testimonial->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $testimonial->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
