<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $description ?? null }}">
    <meta name="author" content="{{ config('app.name') }}">

    @stack('metadata')

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- FontAwesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.8.2/js/all.js" integrity="sha384-DJ25uNYET2XCl5ZF++U8eNxPWqcKohUUBUpKGlNLMchM7q4Wjg2CUpjHLaL8yYPH" crossorigin="anonymous"></script>

    <!-- Global CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/prism/prism.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/lightbox/dist/ekko-lightbox.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/elegant_font/css/style.css') }}">

    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="{{ asset('css/styles.css') }}">

    @stack('css')

</head>

<body class="body-orange">
<div class="page-wrapper">

    <!-- ******Header****** -->
    <header id="header" class="header">
        <div class="container">
            <div class="branding">
                <h1 class="logo">
                    <a href="{{ route('index') }}">
                        <span aria-hidden="true" class="icon_documents_alt icon"></span>
                        <span class="text-highlight">{{ config('app.name') }}</span><span class="text-bold">ID</span>
                    </a>
                </h1>
            </div><!--//branding-->

          <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="{{ route('index') }}">@lang('Beranda')</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ !request()->routeIs('word.create') ?: 'active' }}">
                  <a class="nav-link" href="{{ route('word.create') }}">@lang('Tambah Istilah')</a>
                </li>

                <li class="nav-item {{ !request()->routeIs('summary.weekly') ?: 'active' }}">
                  <a class="nav-link" href="{{ route('summary.weekly') }}">@lang('Ringkasan Mingguan')</a>
                </li>

                @auth
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">
                      @lang('Keluar') ({{ auth()->user()->name }})
                    </a>
                  </li>
                @endif

                @guest
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @lang('Kontributor')
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('login') }}">@lang('Masuk')</a>
                    <a class="dropdown-item" href="{{ route('password.request') }}">@lang('Lupa Sandilewat')</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('register') }}">@lang('Daftar')</a>
                  </div>
                </li>
                @endguest
              </ul>
            </div>
          </nav>

            <div class="top-search-box">
              <form class="form-inline search-form justify-content-center" action="{{ route('word.search') }}" method="get">
                @if (request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif

                @if (!empty($category) and request()->routeIs('word.category'))
                  <input type="hidden" name="kategori" value="{{ $category->slug }}">
                  <input type="text" value="{{ request('katakunci') }}" placeholder="@lang('Cari istilah dalam bidang :category...', ['category' => strtolower($category->name)])" name="katakunci" class="form-control search-input" autocomplete="off">
                @else
                  <input type="text" value="{{ request('katakunci') }}" placeholder="@lang('Istilah dalam bahasa asing atau Indonesia...')" name="katakunci" class="form-control search-input" autocomplete="off">
                @endif

                <button type="submit" class="btn search-btn" value="Search"><i class="fas fa-search"></i></button>
            </form>
          </div>

        </div><!--//container-->
    </header><!--//header-->

    <div class="doc-wrapper" id="app">
      @yield('content')
    </div>

</div><!--//page-wrapper-->

<footer id="footer" class="footer text-center">
    <div class="container">
        <!--/* This template is released under the Creative Commons Attribution 3.0 License. Please keep the attribution link below when using for your own project. Thank you for your support. :) If you'd like to use the template without the attribution, you can buy the commercial license via our website: themes.3rdwavemedia.com */-->
        <small class="copyright">
            @lang('Designed with :credit for developers', [
                'credit' => '<i class="fas fa-heart"></i> by <a href="https://themes.3rdwavemedia.com/" target="_blank">Xiaoying Riley</a>'
            ])
        </small>

    </div><!--//container-->
</footer><!--//footer-->


<!-- Main Javascript -->
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-3.3.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/prism/prism.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-scrollTo/jquery.scrollTo.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/lightbox/dist/ekko-lightbox.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/stickyfill/dist/stickyfill.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>

<script src="{{ asset('js/app.js') }}"></script>

@stack('js')

</body>
</html>

