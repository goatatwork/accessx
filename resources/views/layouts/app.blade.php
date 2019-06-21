<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="/css/shards.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css" rel="stylesheet">

    <!-- Google Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Font Awesome -->
    <script src="https://use.fortawesome.com/a97ca672.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

    @if (Auth::check())
    <script>
        window.Laravel = {!! json_encode([
            'user_id' => Auth::id(),
        ]) !!};
    </script>
    @endif

</head>
<body>
    <div id="app">

        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExample04">
                <ul class="navbar-nav ml-auto mr-3">

<!--                     @if (Auth::check())
                        <presence-information></presence-information>
                    @endif -->

                </ul>
                <ul class="navbar-nav ml-auto mr-3">
                    <!--
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <!-- <a class="dropdown-item" href="#">Action</a> -->
                            <a class="dropdown-item" href="/horizon" target="_blank"><i class="fab fa-laravel mr-1"></i>Horizon</a>
                            <a class="dropdown-item" href="http://{{ request()->headers->get('host') }}:9001" target="_blank"><i class="fab fa-docker mr-1"></i>Portainer</a>
                            <a href="{{ route('logout') }}" class="dropdown-item"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                               <!-- <i class="material-icons">exit_to_app</i> -->
                               <i class="fas fa-power-off mr-1"></i>
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                </ul>
                <form class="form-inline my-2 my-md-0" style="display:none;">
                    <input class="form-control" type="text" placeholder="Search">
                </form>
            </div>
        </nav>

        <div class="container-fluid vh-100">
            <div class="row h-100 vh-100">
                <div class="sidebar col-md-2 text-right bg-dark">
                    <ul class="nav flex-md-column align-self-start justify-content-between">
                        <li class="nav-item nav-parent px-1 nav-item nav-parent py-4 px-1 d-sm-block text-sm-center text-md-right w-100">
                            <a class="nav-link px-0 py-0 text-warning lead " href="/">
                                <span class="fas fa-fw fa-tachometer-alt"></span> DASHBOARD
                            </a>
                        </li>
                        <li class="nav-item">
                            <hr class="bg-dark">
                        </li>

                        @can('view_customers')
                        <li class="nav-item text-warning nav-parent px-1">
                            <span class="fas fa-fw fa-users"></span> CUSTOMERS
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link px-0 py-0 text-white" href="/provisioning">Provisioned</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-0 py-0 text-white" href="/customers">All Customers</a>
                                </li>
                            </ul>
                        </li>
                        @endcan


                        <li class="nav-item">
                            <hr class="bg-dark">
                        </li>

                        @can('view_network')
                        <li class="nav-item text-warning nav-parent px-1">
                            <span class="fas fa-fw fa-sitemap"></span> NETWORK
                            <ul class="nav flex-column">
                                @can('view_aggregators')
                                <li class="nav-item">
                                    <a class="nav-link px-0 py-0 text-white" href="/infrastructure/aggregators">Aggregators</a>
                                </li>
                                @endcan
                                @can('view_dhcp')
                                <li class="nav-item">
                                    <a class="nav-link px-0 py-0 text-white" href="/dhcp">DHCP</a>
                                </li>
                                @endcan
                                @can('view_onts')
                                <li class="nav-item">
                                    <a class="nav-link px-0 py-0 text-white" href="/onts">ONTs</a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan


                        <li class="nav-item">
                            <hr class="bg-dark">
                        </li>

                        @can('view_system')
                        <li class="nav-item text-warning nav-parent px-1">
                            <span class="fas fa-fw fa-cogs"></span> SYSTEM
                            <ul class="nav flex-column">

                                @can('view_users')
                                <li class="nav-item">
                                    <a class="nav-link px-0 py-0 text-white" href="/users">Users</a>
                                </li>
                                @endcan

                                <li class="nav-item">
                                    <a class="nav-link px-0 py-0 text-white" href="/settings">Settings</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-0 py-0 text-white" href="/activity_logs">Activity Logs</a>
                                </li>
                            </ul>
                        </li>
                        @endcan
                    </ul>
                </div> <!-- /sidebar -->

                <div class="col main">

                    @yield('content')

                    @if (Auth::check())
                        <echo-messages></echo-messages>
                    @endif

                </div>
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div>
<!-- Scripts -->
<script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>

<script src="{{ asset('js/app.js') }}"></script>
<script src="/js/shards.min.js"></script>

@yield('footer-scripts')
</body>
</html>
