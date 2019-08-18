<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>{{ $title ?? config('app.name') }}</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $title }}">
    <meta name="author" content="{{ config('app.name') }}">

    <meta property="og:title" content="{{ config('app.name') }}" />
    <meta property="og:description" content="{{ $title }}">
    <meta property="og:url" content="{{ route('index') }}" />

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- FontAwesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.8.2/js/all.js" integrity="sha384-DJ25uNYET2XCl5ZF++U8eNxPWqcKohUUBUpKGlNLMchM7q4Wjg2CUpjHLaL8yYPH" crossorigin="anonymous"></script>

    <!-- Global CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/elegant_font/css/style.css') }}">

    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="{{ asset('css/styles.css') }}">

    @stack('css')
</head>

<body class="landing-page">
<div class="page-wrapper">

    <!-- ******Header****** -->
    <header class="header text-center">
        <div class="container">
            <div class="branding">
                <h1 class="logo">
                    <span aria-hidden="true" class="icon_documents_alt icon"></span>
                    <span class="text-highlight">{{ config('app.name') }}</span><span class="text-bold">ID</span>
                </h1>
            </div><!--//branding-->
            <div class="tagline">
                <p>{{ $title }}</p>
            </div><!--//tagline-->

            <div class="main-search-box pt-3 pb-4 d-inline-block">
                <form class="form-inline search-form justify-content-center" action="{{ route('word.search') }}" method="get">
                    <input type="text" placeholder="@lang('Istilah dalam bahasa asing atau Indonesia...')" name="katakunci" class="form-control search-input" autocomplete="off">
                    <button type="submit" class="btn search-btn" value="Search"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div><!--//container-->
    </header><!--//header-->

    <section class="cards-section text-center">
        <div class="container">
            <h2 class="title">@lang('Cari istilah dalam berbagai kategori!')</h2>
            <div class="intro">
              <p>@lang('Mulai dengan mencari istilah asing maupun istilah dalam bahasa Indonesia. Istilah yang sama bisa memiliki padanan yang berbeda untuk kategori yang berbeda.')</p>
              <div class="cta-container">
                <a class="btn btn-primary btn-cta" href="{{ route('about') }}"><i
                    class="fa fa-question-circle fa-fw"></i> @lang('Tentang kami')</a>
                <a class="btn btn-primary btn-cta" href="{{ route('contact') }}"><i
                    class="fa fa-at fa-fw"></i> @lang('Kontak')</a>
              </div>

            </div><!--//intro-->
            <div id="cards-wrapper" class="cards-wrapper row">
              @foreach ($categories as $index => $category)

                @if (Agent::isMobile())
                  @if ($index % 5 == 0 AND $index != 0)
                    <div class="item item-orange col-lg-4 col-md-6 col-12">
                      @include('layouts.adsense.responsive')
                    </div>
                  @endif
                @endif

                <div class="item item-orange col-lg-4 col-md-6 col-12">
                  <div class="item-inner">
                    <div class="icon-holder">
                      <i class="icon fa fa-{{ $category->metadata['icon'] ?? 'list' }} fa-fw"></i>
                    </div><!--//icon-holder-->
                    <h3 class="title">{{ $category->name }}</h3>
                    <p class="intro">@lang(':count istilah', ['count' => $number->format($category->words_count)])</p>
                    <p class="intro">{{ \Illuminate\Support\Str::substr($category->description, 0, 100) }}</p>
                    <a class="link" href="{{ route('word.category', $category) }}"></a>
                  </div>
                </div>
              @endforeach
            </div><!--//cards-->

        </div><!--//container-->
    </section><!--//cards-section-->
</div><!--//page-wrapper-->

<footer class="footer text-center">
    <div class="container">
        <!--/* This template is released under the Creative Commons Attribution 3.0 License. Please keep the attribution link below when using for your own project. Thank you for your support. :) If you'd like to use the template without the attribution, you can buy the commercial license via our website: themes.3rdwavemedia.com */-->
        <small class="copyright">Designed with <i class="fas fa-heart"></i> by <a href="https://themes.3rdwavemedia.com/" target="_blank">Xiaoying Riley</a> for developers</small>

    </div><!--//container-->
</footer><!--//footer-->


<!-- Main Javascript -->
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-3.3.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/stickyfill/dist/stickyfill.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>

@stack('js')

</body>
</html>

