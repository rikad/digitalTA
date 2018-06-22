<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Digital TA - Teknik Fisika ITB</title>

    <!-- Styles -->
    <link href="/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">

    @yield('css')

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">Digital TA - Teknik Fisika ITB</a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                        @role('admin')
                			<!-- <li><a href="{{ url('/home') }}">Dashboard</a></li> -->
                        @endrole
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <!-- <li><a href="{{ route('register') }}">Register</a></li> -->
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="#"
                                            onclick="event.preventDefault(); getUserInfo();"
                                            data-toggle="modal" data-target="#modalProfile">
                                            Ubah Profile User
                                        </a>

                                        <a href="#"
                                            onclick="event.preventDefault();"
                                            data-toggle="modal" data-target="#modalPasswd">
                                            Ubah Password
                                        </a>

                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

<!--Modal Bulk-->
  <div class="modal fade" id="modalPasswd" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ubah Password</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="/changePassword"> {{ csrf_field() }}
            <div class="form-group">
                <label for="pwd">Password Saat Ini:</label>
                <input type="password" class="form-control" id="pwdc" name="passwordCurrent">
            </div>

            <div class="form-group">
                <label for="pwd">Password Baru:</label>
                <input type="password" class="form-control" id="pwdn" name="passwordNew">
            </div>

            <div class="form-group">
                <label for="pwd">Password Baru (Konfirmasi):</label>
                <input type="password" class="form-control" id="pwdn2" name="passwordNew2">
            </div>

              <button type="submit" class="btn btn-default">Ganti Password</button>
          </form>
        </div>
      </div>
      
    </div>
  </div>
  <!---->

  <!--Modal Bulk-->
  <div class="modal fade" id="modalProfile" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ubah Informasi User</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="/updateUserInfo"> {{ csrf_field() }}
            <div class="form-group">
                <label for="pwd">No. Induk:</label>
                <input type="text" class="form-control" id="ni" name="ni">
            </div>

            <div class="form-group">
                <label for="pwd">Nama User:</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>

            <div class="form-group">
                <label for="pwd">Email:</label>
                <input type="text" class="form-control" id="email" name="email">
            </div>

              <button type="submit" class="btn btn-default">Update Informasi</button>
          </form>
        </div>
      </div>
      
    </div>
  </div>
  <!---->

        <div style="margin: 5em"></div>
        @if (session()->has('flash_notification.message'))
            <div class="container">
                <div class="alert alert-{{ session()->get('flash_notification.level') }}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {!! session()->get('flash_notification.message') !!}
                </div>
            </div>
        @endif

        @yield('content')


        <footer style="background-color: #2C3E50; color: #ffffff; padding: 2em; width: 100%; text-align:center">
          <p>&copy TATF Team</p>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="/js/jquery-3.1.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script>
        function getUserInfo(){
            $.ajax({
                url: '/getUserInfo',
                type: 'GET',
                dataType: 'json',
                error: function() {
                    alert('error fetch data, please refresh this page again');
                },
                success: function(res) {
                    document.getElementById('ni').value=res.no_induk ; 
                    document.getElementById('name').value=res.name ; 
                    document.getElementById('email').value=res.email ; 
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
