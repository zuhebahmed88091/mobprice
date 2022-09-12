<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('settings.SITE_NAME') }} - Help Center</title>

    <!-- Common Styles -->
    <link rel="stylesheet" href="{{ asset('css/member.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/adminLTE/docs.css') }}">

    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <style>
        .child-menu p {
            margin: 0;
            overflow: hidden;
            white-space: normal;
        }

        .child-menu i.fa {
            float: left;
            margin-top: 3px;
        }

        .treeview > a > span.main-title {
            white-space: normal;
            width: 160px;
            display: inline-flex;
        }

        @media (min-width: 768px) {
            .sidebar-mini:not(.sidebar-mini-expand-feature).sidebar-collapse .sidebar-menu > li:hover > a > .pull-right-container {
                left: -999px !important;
            }

            .sidebar-mini:not(.sidebar-mini-expand-feature).sidebar-collapse .sidebar-menu > li:hover > a > span {
                padding: 12px 0 12px 20px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }
    </style>

</head>
<body class="hold-transition skin-black-light sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="/" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>HC</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Help Center</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="btn-group">
                <a href="#" class="btn btn-primary navbar-btn dropdown-toggle" data-toggle="dropdown">
                    {{ $selectedProduct->title }} <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-menu-xs">
                    @foreach($products as $product)
                        <li><a href="{{ url('/help-center/' . $product->id) }}">{{ $product->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/') }}">home</a></li>
                    <li><a href="{{ url('/help-center/my-tickets') }}">My Tickets</a></li>
                    <li>
                        <a href="{{ url('/help-center/create-ticket') }}">
                            Create Ticket
                        </a>
                    </li>

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
        </nav>
    </header><!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- search form -->
            <form method="get" class="sidebar-form" id="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" aria-label="search" placeholder="Search..." id="search-input">
                    <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
                </div>
            </form>
            <!-- /.search form -->

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">DOCUMENTATIONS</li>

                {!! $categoryMenuTree !!}
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <article>
            <!-- Content Header (Page header) -->
            <section class="content-header">
                @yield('content-header')
            </section>

            <!-- Main content -->
            <section class="content container-fluid">
                @yield('content')
            </section>
            <!-- /.content -->
        </article>
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            Version: 1.0
        </div>
        <!-- Default to the left -->
        <div>Copyright © {{ date('Y') }} <b>{{ config('settings.SITE_NAME') }}</b> All Right Reserved</div>
    </footer>
</div>
<!-- ./wrapper -->

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script>
    $(function () {
        $('#sidebar-form').on('submit', function (e) {
            e.preventDefault();
        });

        $('.sidebar-menu li.active').data('lte.pushmenu.active', true);

        let sidebarMenuLiObj = $('.sidebar-menu li');

        $('#search-input').on('keyup', function () {
            let term = $('#search-input').val().trim();
            if (term.length === 0) {
                sidebarMenuLiObj.each(function () {
                    $(this).show(0);
                    $(this).removeClass('active');
                    if ($(this).data('lte.pushmenu.active')) {
                        $(this).addClass('active');
                    }
                });
                return;
            }

            sidebarMenuLiObj.each(function () {
                if ($(this).text().toLowerCase().indexOf(term.toLowerCase()) === -1) {
                    $(this).hide(0);
                    $(this).removeClass('pushmenu-search-found', false);

                    if ($(this).is('.treeview')) {
                        $(this).removeClass('active');
                    }
                } else {
                    $(this).show(0);
                    $(this).addClass('pushmenu-search-found');

                    if ($(this).is('.treeview')) {
                        $(this).addClass('active');
                    }

                    let parent = $(this).parents('li').first();
                    if (parent.is('.treeview')) {
                        parent.show(0);
                    }
                }

                if ($(this).is('.header')) {
                    $(this).show();
                }
            });

            $('.sidebar-menu li.pushmenu-search-found.treeview').each(function () {
                $(this).find('.pushmenu-search-found').show(0);
            });
        });
    });
</script>

</body>
</html>
