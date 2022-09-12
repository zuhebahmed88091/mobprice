@extends('layouts.front_end_base')

@section('css')
@endsection

@section('content')
        <div class="outer-wrap section-content-wrap profile-page">
            <div class="content-wrap-inner">
                <div class="container">
                    @if(Session::has('success_message'))
                        <div class="alert alert-success mt-4">
                            <span class="glyphicon glyphicon-ok"></span>
                            {!! session('success_message') !!}

                            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(Session::has('error_message'))
                        <div class="alert alert-danger mt-4">
                            <span class="fa fa-exclamation-triangle"></span>
                            {!! session('error_message') !!}

                            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="row mt-4 mb-2">
                        <div class="col-lg-8">
                            <div class="box box-default">

                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        Profile Information
                                    </h3>
                                </div>

                                <form method="POST" action="{{ route('members.profileUpdate', $customer->id) }}" enctype="multipart/form-data" >
                                    {{ csrf_field() }}
                                    <input name="_method" type="hidden" value="PUT">

                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                                    <label for="name">Full Name <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="name" type="text" id="name"
                                                           value="{{ old('name', optional($customer)->name) }}"
                                                           minlength="1" maxlength="255" required>
                                                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                                    <label for="email">Email <span class="text-danger">*</span></label>
                                                    <input readonly class="form-control" name="email" type="text" id="email"
                                                           value="{{ old('email', optional($customer)->email) }}"
                                                           minlength="1" maxlength="255" required>
                                                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                                                    <label for="phone">Mobile <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="phone" type="text" id="phone"
                                                           value="{{ old('phone', optional($customer)->phone) }}"
                                                           minlength="11" maxlength="11" required>
                                                    {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                                                    <label for="address">Address <span class="text-danger">*</span></label>
                                                    <textarea rows="2" cols="50" class="form-control" name="address" id="address" required>{{ old('address', optional($customer)->address) }}</textarea>
                                                    {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary btn-flat pull-right">Update Profile
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="box box-default">

                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        Change Password
                                    </h3>
                                </div>

                                <form method="POST" action="{{ route('members.changePassword', $customer->id) }}">
                                    {{ csrf_field() }}
                                    <input name="_method" type="hidden" value="PUT">

                                    <div class="box-body">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div
                                                    class="form-group {{ $errors->has('current_password') ? 'has-error' : '' }}">
                                                    <label for="current_password">Current Password</label>
                                                    <input class="form-control" name="current_password" id="current_password"
                                                           type="password" minlength="1" maxlength="20" required>
                                                    {!! $errors->first('current_password', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group {{ $errors->has('new_password') ? 'has-error' : '' }}">
                                                    <label for="new_password">New Password</label>
                                                    <input class="form-control" name="new_password" id="new_password"
                                                           type="password" minlength="1" maxlength="20" required>
                                                    {!! $errors->first('new_password', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div
                                                    class="form-group {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
                                                    <label for="confirm_password">Confirm Password</label>
                                                    <input class="form-control" name="confirm_password" id="confirm_password"
                                                           type="password" minlength="1" maxlength="20" required>
                                                    {!! $errors->first('confirm_password', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary btn-flat pull-right">
                                            Update Password
                                        </button>
                                    </div>
                                </form>

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


