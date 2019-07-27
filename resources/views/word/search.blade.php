@extends('layouts.app')

@push('metadata')
  <meta property="og:title" content="{{ $title }}" />
  <meta property="og:description" content="{{ $description }}">
  <meta property="og:url" content="{{ \Illuminate\Support\Facades\Request::fullUrl() }}" />
@endpush

@section('content')
  <div class="container">
    @if (request('katakunci'))
    <div id="doc-header" class="doc-header text-center">
      <h1 class="doc-title"><span aria-hidden="true" class="icon icon_search-2"></span> {{ request('katakunci') }}</h1>
      <div class="meta">@lang('ditemukan :count hasil pencarian untuk katakunci tersebut', ['count' => $words->total()])</div>
      <hr>
    </div>
    @endif

    <div id="showcase" class="doc-body row">
      <div class="doc-content col-md-9 col-12 order-1">
{{--        <div class="callout-block callout-info">--}}
{{--          <div class="icon-holder">--}}
{{--            <svg class="svg-inline--fa fa-info-circle fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path></svg><!-- <i class="fas fa-info-circle"></i> -->--}}
{{--          </div><!--//icon-holder-->--}}
{{--          <div class="content">--}}
{{--            <h4 class="callout-title">@lang('Halo, :name!', ['name' => data_get(auth()->user(), 'name', 'orang asing')])</h4>--}}
{{--            <p>@lang('Bantu <strong>:app</strong> untuk memperkaya kosakata pada pangkalan data dengan cara menambah kata asing beserta padanan dalam bahasa Indonesia. Klik pada <a href=":link" class>tautan berikut</a> untuk menambah kata. Anda tidak perlu mendaftar sebagai anggota untuk menambah kata.', [--}}
{{--              'app' => config('app.name'),--}}
{{--              'link' => route('word.create'),--}}
{{--            ])</p>--}}
{{--          </div><!--//content-->--}}
{{--        </div>--}}

        <div class="content-inner">
          @if ($words->total() == 0)
            <p>@lang('Penelusuran Anda - <strong>:keyword</strong> - tidak cocok dengan dokumen apa pun.', ['keyword' => request('katakunci')])</p>
            <p>@lang('Saran:')</p>
            <ul>
              <li>@lang('Pastikan semua kata dieja dengan benar.')</li>
              <li>@lang('Coba kata kunci yang lain.')</li>
              <li>@lang('Coba kata kunci yang lebih umum.')</li>
            </ul>
          @endif
          @each('word.content', $words, 'word')
        </div><!--//content-inner-->


        {{ $words->links() }}
      </div><!--//doc-content-->
      <div class="doc-sidebar col-md-3 col-12 order-0 d-none d-md-flex">
        <div id="doc-nav" class="doc-nav">
          <nav id="doc-menu" class="nav doc-menu flex-column sticky">
            @foreach ($words as $word)
            <a class="nav-link scrollto" href="#{{ $word->slug }}">{{ $word->origin }}</a>
            @endforeach
          </nav><!--//doc-menu-->
        </div>
      </div><!--//doc-sidebar-->
    </div><!--//doc-body-->
  </div>
@endsection
