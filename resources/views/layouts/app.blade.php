<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('settings.SITE_NAME') }}</title>
     
    <!-- Common Styles -->
    <link rel="stylesheet" href="{{ asset('themes/innolytic/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Alertify -->
    <link rel="stylesheet" href="{{ asset('alertify/alertify.core.css') }}"/>
    <link rel="stylesheet" href="{{ asset('alertify/alertify.default.css') }}"/>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Custom Styles -->
@yield('css')

<!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <script>
        let dateFormat = '{{ \App\Helpers\CommonHelper::getJsDisplayDateFormat() }}';
        let maxUploadSize = parseInt('{{ config('settings.MAXIMUM_FILE_SIZE') }}');
        let maxUploadSizeInByte = maxUploadSize * 1024 * 1024;
        let mediaUploadRoute = '{{ route('media.summerNoteUpload') }}';
        let defaultDashboardTime = parseInt('{{ config('settings.DEFAULT_DASHBOARD_TIME') }}');
        let loaderImageHtml = '<img src="{{ asset('images/loader-64.gif') }}" class="ajax-loader">';
    </script>

</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper" id="app">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="{{ url('/') }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>{{ config('settings.SITE_SHORT_NAME') }}</b></span>

            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg pull-left">
                    <img src="{{ asset('storage/sites/' . config('settings.SITE_LOGO')) }}" alt="Site Logo"
                         style="height: 35px;margin-right: 10px;">
                    <b>{{ config('settings.SITE_NAME') }}</b>
                </span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">

            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <!-- Nav bar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the nav bar-->
                            @if (Auth::user()->uploadedFile()->exists())
                                <img src="{{ asset('storage/profiles/' . Auth::user()->uploadedFile->filename) }}"
                                     class="user-image" alt="User Image">
                            @elseif(Auth::user()->gender == 'Male')
                                <img src="{{ asset('images/avatar_male.png') }}" class="user-image" alt="User Image">
                            @elseif(Auth::user()->gender == 'Female')
                                <img src="{{ asset('images/avatar_female.png') }}" class="user-image" alt="User Image">
                        @endif

                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ optional(Auth::user())->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                @if (Auth::user()->uploadedFile()->exists())
                                    <img src="{{ asset('storage/profiles/' . Auth::user()->uploadedFile->filename) }}"
                                         class="img-circle" alt="User Image">
                                @elseif(Auth::user()->gender == 'Male')
                                    <img src="{{ asset('images/avatar_male.png') }}" class="img-circle"
                                         alt="User Image">
                                @elseif(Auth::user()->gender == 'Female')
                                    <img src="{{ asset('images/avatar_female.png') }}" class="img-circle"
                                         alt="User Image">
                                @endif

                                <p>
                                    {{ optional(Auth::user())->name }}
                                    - {{ optional(Auth::user()->roles())->first()->name }}
                                    <small>Member since {{ optional(Auth::user())->created_at }}</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ route('users.show', optional(Auth::user())->id ) }}"
                                       class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        Sign out
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li {{ (Request::is('settings*') ? 'class=active' : '') }}>
                        <a href="{{ route('settings.group') }}"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- search form (Optional) -->
            <form method="get" class="sidebar-form" id="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" autocomplete="off" class="form-control" aria-label="search"
                           placeholder="Search..." id="search-input">
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
                <li class="header">MAIN NAVIGATION</li>
                @if (App\Helpers\CommonHelper::isCapable('dashboard.index'))
                    <li {{ (Request::routeIs('dashboard.*') ? 'class=active' : '') }}>
                        <a href="{{ route('dashboard.index') }}">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable('brands.index'))
                    <li {{ (Request::routeIs('brands.*') ? 'class=active' : '') }}>
                        <a href="{{ route('brands.index') }}">
                            <i class="fa fa-apple"></i> <span>Brands</span>
                        </a>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable('mobiles.index'))
                    <li {{ (Request::routeIs('mobiles.*') ? 'class=active' : '') }}>
                        <a href="{{ route('mobiles.index') }}">
                            <i class="fa fa-mobile" style="font-size: 18px;"></i> <span>Mobiles</span>
                        </a>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable('mobile_regions.index'))
                    <li {{ (Request::routeIs('mobile_regions.*') ? 'class=active' : '') }}>
                        <a href="{{ route('mobile_regions.index') }}">
                            <i class="fa fa-globe"></i> <span>Regions</span>
                        </a>
                    </li>
                @endif


                {{-- @if (App\Helpers\CommonHelper::isCapable('mobile_prices.index'))
                    <li {{ (Request::routeIs('mobile_prices.*') ? 'class=active' : '') }}>
                        <a href="{{ route('mobile_prices.index') }}">
                            <i class="fa fa-tag"></i> <span>Variation Prices</span>
                        </a>
                    </li>
                @endif --}}

                @if (App\Helpers\CommonHelper::isCapable('news.index'))
                    <li {{ (Request::routeIs('news.*') ? 'class=active' : '') }}>
                        <a href="{{ route('news.index') }}">
                            <i class="fa fa-newspaper-o"></i> <span>News</span>
                        </a>
                    </li>
                @endif
                @if (App\Helpers\CommonHelper::isCapable('opinions.index'))
                    <li {{ (Request::routeIs('opinions.*') ? 'class=active' : '') }}>
                        <a href="{{ route('opinions.index') }}">
                            <i class="fa fa-star"></i> <span>User Opinions</span>
                        </a>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable([
               'filter_tabs.index',
               'filter_groups.index',
               'filter_options.index'
               ]))
                    <li class="{{ ((Request::routeIs('filter_tabs*')
                                || Request::routeIs('filter_sections*')
                                || Request::routeIs('filter_options*')
                               ) ? 'active treeview menu-open' : 'treeview') }} ">
                        <a href="#">
                            <i class="fa fa-filter"></i>
                            <span>Filters</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            @if (App\Helpers\CommonHelper::isCapable('filter_tabs.index'))
                                <li {{ (Request::routeIs('filter_tabs.*') ? 'class=active' : '') }}>
                                    <a href="{{ route('filter_tabs.index') }}">
                                        <i class="fa fa-sitemap"></i> <span>Tabs</span>
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('filter_sections.index'))
                                <li {{ (Request::routeIs('filter_sections.*') ? 'class=active' : '') }}>
                                    <a href="{{ route('filter_sections.index') }}">
                                        <i class="fa fa-columns"></i> <span>Sections</span>
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('filter_options.index'))
                                <li {{ (Request::routeIs('filter_options.*') ? 'class=active' : '') }}>
                                    <a href="{{ route('filter_options.index') }}">
                                        <i class="fa fa-th-list"></i> <span>Options</span>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable('affiliates.index'))
                    <li {{ (Request::routeIs('affiliates.*') ? 'class=active' : '') }}>
                        <a href="{{ route('affiliates.index') }}">
                            <i class="fa fa-handshake-o"></i> <span>Affiliates</span>
                        </a>
                    </li>
                @endif

                {{-- @if (App\Helpers\CommonHelper::isCapable('tickets.index'))
                    <li {{ (Request::routeIs('tickets.*') ? 'class=active' : '') }}>
                        <a href="{{ route('tickets.index') }}">
                            <i class="fa fa-ticket"></i> <span>Tickets</span>
                        </a>
                    </li>
                @endif --}}

                @if (App\Helpers\CommonHelper::isCapable('tags.index'))
                    <li {{ (Request::routeIs('tags*') ? 'class=active' : '') }}>
                        <a href="{{ route('tags.index') }}">
                            <i class="fa fa-tag"></i> <span>Tags</span>
                        </a>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable('testimonials.index'))
                    <li {{ (Request::routeIs('testimonials*') ? 'class=active' : '') }}>
                        <a href="{{ route('testimonials.index') }}">
                            <i class="fa fa-quote-left"></i> <span>Testimonials</span>
                        </a>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable([
                'modules.index',
                'roles.index',
                'permissions.index',
                'users.index',
                'event_logs.index'
                ]))
                    <li class="{{ ((Request::routeIs('modules*')
                                || Request::routeIs('roles*')
                                || Request::routeIs('permissions*')
                                || Request::routeIs('users*')
                                || Request::routeIs('event_logs*')
                               ) ? 'active treeview menu-open' : 'treeview') }} ">
                        <a href="#">
                            <i class="fa fa-shield"></i>
                            <span>Admin Privilege</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            @if (App\Helpers\CommonHelper::isCapable('modules.index'))
                                <li {{ (Request::routeIs('modules*') ? 'class=active' : '') }}>
                                    <a href="{{ route('modules.index') }}">
                                        <i class="fa fa-cubes"></i> Modules
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('roles.index'))
                                <li {{ (Request::routeIs('roles*') ? 'class=active' : '') }}>
                                    <a href="{{ route('roles.index') }}">
                                        <i class="fa fa-users"></i> Roles
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('permissions.index'))
                                <li {{ (Request::routeIs('permissions*') ? 'class=active' : '') }}>
                                    <a href="{{ route('permissions.index') }}">
                                        <i class="fa fa-key"></i> Permissions
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('users.index'))
                                <li {{ (Request::routeIs('users*') ? 'class=active' : '') }}>
                                    <a href="{{ route('users.index') }}">
                                        <i class="fa fa-user"></i> Users
                                    </a>
                                </li>
                            @endif

                            @if (App\Helpers\CommonHelper::isCapable('event_logs.index'))
                                <li {{ (Request::routeIs('event_logs*') ? 'class=active' : '') }}>
                                    <a href="{{ route('event_logs.index') }}">
                                        <i class="fa fa-file-archive-o"></i> Event Logs
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- @if (App\Helpers\CommonHelper::isCapable('countries.index'))
                    <li {{ (Request::routeIs('countries*') ? 'class=active' : '') }}>
                        <a href="{{ route('countries.index') }}">
                            <i class="fa fa-globe"></i> <span>Countries</span>
                        </a>
                    </li>
                @endif --}}

                @if (App\Helpers\CommonHelper::isCapable('contact_us.index'))
                    <li {{ (Request::routeIs('contact_us*') ? 'class=active' : '') }}>
                        <a href="{{ route('contact_us.index') }}">
                            <i class="fa fa-envelope"></i> <span>Contact Us</span>
                        </a>
                    </li>
                @endif

                @if (App\Helpers\CommonHelper::isCapable('settings.index'))
                    <li {{ (Request::routeIs('settings*') ? 'class=active' : '') }}>
                        <a href="{{ route('settings.group') }}">
                            <i class="fa fa-cogs"></i> <span>Settings</span>
                        </a>
                    </li>
                @endif

            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @yield('content-header')
        </section>

        <!-- Main content -->
        <section class="content container-fluid">
            @yield('content')
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            {{ config('settings.VERSION') }}
        </div>
        <!-- Default to the left -->
        {{ config('settings.FOOTER_TEXT') }}
    </footer>

</div>
<!-- ./wrapper -->

<!-- Plugins and package scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('alertify/alertify.min.js') }}"></script>

<!-- Site comment scripts -->
<script src="{{ asset('moment/min/moment.min.js') }}"></script>
<script src="{{ asset('bootstrap-daterangepicker/daterangepicker-custom.js') }}"></script>

@yield('javascript')

<script src="{{ asset('js/common.js') }}"></script>
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

@if(Session::pull('isConfigCache'))
    @php(Artisan::call('config:cache'))
@endif

</body>

</html>
