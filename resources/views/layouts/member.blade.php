<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Help Center</title>

    <link rel="stylesheet" href="{{ asset('css/member.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/adminLTE/docs.css') }}">

    <!-- Custom Styles -->
    @yield('css')

    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <style>
        .content-wrapper {
            font-size: 14px;
        }

        .content-header {
            margin-bottom: 16px;
        }

        .content-header h1 {
            padding: 20px 20px 0 0;
            font-size: 24px;
            font-weight: 400;
            border-bottom: none;
        }

        .form-group.has-error .select2-selection {
            border-color: #dd4b39;
            box-shadow: none;
        }

        .wysihtml5-sandbox.has-error {
            border-color: #dd4b39 !important;
            box-shadow: none;
        }

        .timeline-header b {
            color: #00a7d0;
        }

        .content-header > .breadcrumb {
            right: 0;
        }

        .timeline > li > .timeline-item {
            margin-left: 80px;
        }

        .timeline > li > img.circular {
            width: 60px;
            height: 60px;
            line-height: 60px;
            position: absolute;
            border-radius: 50%;
            background-color: #c6c6c6;
            padding: 1px;
        }

        /****************************** Rating star **************************/

        .rate {
            float: left;
        }

        .rate:not(:checked) > input {
            position:absolute;
            top:-9999px;
        }

        .rate:not(:checked) > label {
            float:right;
            width:1em;
            overflow:hidden;
            white-space:nowrap;
            cursor:pointer;
            font-size:50px;
            color:#ccc;
        }
        .rate:not(:checked) > label:before {
            content: '★ ';
        }
        .rate > input:checked ~ label {
            color: #ffc700;
        }
        .rate:not(:checked) > label:hover,
        .rate:not(:checked) > label:hover ~ label {
            color: #deb217;
        }
        .rate > input:checked + label:hover,
        .rate > input:checked + label:hover ~ label,
        .rate > input:checked ~ label:hover,
        .rate > input:checked ~ label:hover ~ label,
        .rate > label:hover ~ input:checked ~ label {
            color: #c59b08;
        }

    </style>

</head>
<body class="hold-transition skin-black-light layout-top-nav">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="container">
                <a href="/">
                    <div class="navbar-header" style="margin: 7px;">
                        <img src="{{ asset('storage/sites/' . config('settings.SITE_LOGO')) }}" alt="Site Logo"
                             style="height: 35px;margin-right: 5px;">
                        <b>{{ config('settings.SITE_NAME') }}</b>
                    </div>
                </a>

                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('/') }}">home</a></li>
                        <li><a href="{{ url('/help-center/my-tickets') }}">My Tickets</a></li>
                        <li><a href="{{ url('/help-center/create-ticket') }}">Create Ticket</a></li>

                        @if (Route::has('login'))
                            @auth
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        Sign out
                                    </a>
                                </li>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            @else
                                <li><a href="{{ url('/login') }}">Login</a></li>
                                @if (Route::has('register'))
                                    <li><a href="{{ url('/register') }}">SignUp</a></li>
                                @endif
                            @endauth
                        @endif

                    </ul>
                </div>
            </div>
        </nav>
    </header><!-- Left side column. contains the logo and sidebar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container">

            <section class="content-header">
                @yield('content-header')
            </section>

            <!-- Main content -->
            <div class="content container-fluid">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Main Footer -->
    <footer class="main-footer">
        <div class="container">
            <!-- To the right -->
            <div class="pull-right hidden-xs">
                Version: 1.0
            </div>
            <!-- Default to the left -->
            <div>Copyright © {{ date('Y') }} <b>{{ config('settings.SITE_NAME') }}</b> All Right Reserved</div>
        </div>
    </footer>
</div>
<!-- ./wrapper -->

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@yield('javascript')

<script src="{{ asset('js/common.js') }}"></script>
<script>
    $(function () {
        // Your comment stuff here
    });
</script>

</body>
</html>
