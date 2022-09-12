@extends('layouts.blank')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>Register</b>{{ config('settings.SITE_NAME') }}</a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg">Sign up to start your session</p>

        <form id="register_form" method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group has-feedback">
                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                    placeholder="{{ __('Name') }}" name="name" value="{{ old('name') }}" required autofocus>

                <span class="glyphicon glyphicon-user form-control-feedback"></span>

                @if ($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif

            </div>

            <div class="form-group has-feedback">

                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                    placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ old('email') }}" required>

                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif

            </div>

            <div class="form-group has-feedback">
                <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                placeholder="{{ __('Mobile Phone e.g: 017******** ') }}" name="phone" value="{{ old('phone') }}"
                onkeyup="digitscheck(this)" minlength="11" maxlength="11" required>
                
                <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                
                @if ($errors->has('phone'))
                <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('phone') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group has-feedback">

                <input id="password" type="password"
                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                    placeholder="{{ __('Password') }}" name="password" required>

                <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group has-feedback">

                <input id="password-confirm" type="password" class="form-control"
                    placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required>

                <span class="glyphicon glyphicon-lock form-control-feedback"></span>

            </div>

            <div class="form-group has-feedback login-captcha">
                <div class="form-group text-center">
                    {!! NoCaptcha::display() !!}
                    @if ($errors->has('g-recaptcha-response'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                    </span>
                    @endif
                    <span class="captcha_status" style="text-align: center"></span>
                </div>
            </div>

            <div class="form-group has-feedback">
                <a href="{{ route('login') }}">Already a members? Sign In</a><br>
                <button type="submit" class="btn btn-primary btn-flat pull-right">
                    {{ __('Register') }}
                </button>
            </div>
            <div class="clearfix"></div>
        </form>

    </div>
</div>
@endsection

@section('javascript')
<script>
    $("#register_form").on('submit',function(e){
            e.preventDefault();
            recaptcha_value= $('.g-recaptcha-response').val();
            if(!recaptcha_value){
                $('.captcha_status').addClass('text-danger').html("Please verify captcha");
            }else{
                $('.captcha_status').html("");
            }
            if(recaptcha_value && $("#register_form").valid())
            {
                this.submit();
            }
        });
    function digitscheck(inp) {
            var check = /[^0-9]/gi;
            inp.value = inp.value.replace(check, "");
    }
    
</script>
{!! NoCaptcha::renderJs() !!}
@endsection