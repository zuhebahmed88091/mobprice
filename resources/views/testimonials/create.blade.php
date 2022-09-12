@extends('layouts.app')

@section('content-header')
    <h1>Create Testimonial</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('testimonials.index') }}">
                <i class="fa fa-dashboard"></i> Testimonials
            </a>
        </li>
        <li class="active">Create</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                Create New Testimonial
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('testimonials.index') }}" class="btn btn-sm btn-info"
                   title="Show All Testimonial">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('testimonials.store') }}" id="create_testimonial_form"
              name="create_testimonial_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('testimonials.form', ['testimonial' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Add testimonial</button>
            </div>
        </form>
    </div>

@endsection