@extends('layouts.blank')

@section('content')

@if(Session::has('info_message'))
<div class="alert alert-danger">
    <span class="fa fa-exclamation-triangle"></span>
    {!! session('info_message') !!}

    <button type="button" class="close" data-dismiss="alert" aria-label="close">
        <span aria-hidden="true">&times;</span>
    </button>

</div>
@endif
@if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
@endif 

<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>Login</b>{{ config('settings.SITE_NAME') }}</a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form id="login_form" action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group has-feedback">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                    name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group has-feedback">
                <input id="password" type="password"
                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                    placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
            </div>
            <input type="hidden" name="review-id" value="{{ isset($_GET['review-id']) ? $_GET['review-id'] : "" }}">
        </form>

        <!-- social-auth-links -->
        <div class="social-auth-links text-center">
            @if (config('settings.IS_ENABLE_FACEBOOK_LOGIN')
            || config('settings.IS_ENABLE_GOOGLE_LOGIN')
            || config('settings.IS_ENABLE_TWITTER_LOGIN')
            || config('settings.IS_ENABLE_ENVATO_LOGIN'))
            <p>- OR -</p>
            @endif

            @if (config('settings.IS_ENABLE_FACEBOOK_LOGIN'))
            <a href="{{ url('/login/facebook') }}" class="btn btn-block btn-social btn-facebook btn-flat">
                <i class="fa fa-facebook"></i> Login with Facebook
            </a>
            @endif

            @if (config('settings.IS_ENABLE_GOOGLE_LOGIN'))
            <a href="{{ url('/login/google') }}" class="btn btn-block btn-social btn-google">
                <i class="fa fa-google"></i> Login with Google
            </a>
            @endif

            @if (config('settings.IS_ENABLE_TWITTER_LOGIN'))
            <a href="{{ url('/login/twitter') }}" class="btn btn-block btn-social btn-twitter">
                <i class="fa fa-twitter"></i> Login with Twitter
            </a>
            @endif

            @if (config('settings.IS_ENABLE_ENVATO_LOGIN'))
            <a href="{{ url('/login/envato') }}" class="btn btn-block btn-social btn-envato">
                <i class="fa fa-leaf"></i> Login with Envato
            </a>
            @endif
        </div>

        @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}">I forgot my password</a><br>
        @endif
        @if (Route::has('register'))
        <a href="{{ Route('register') }}" class="text-center">Register a new membership</a>
        @endif

    </div>
</div>
@endsection

<!-- iCheck -->
@section('javascript')
<script>
    $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
            });
        });
</script>
@endsection