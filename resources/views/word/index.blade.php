@extends('layouts.app')

@push('metadata')
  <meta property="og:title" content="{{ $title }}" />
  <meta property="og:description" content="{{ $description }}">
  <meta property="og:url" content="{{ url()->current() }}" />
@endpush

@section('content')
  <div class="container">
    <div id="doc-header" class="doc-header text-center">
      <h1 class="doc-title"><span aria-hidden="true" class="icon icon_search_alt"></span> @lang('Daftar kata')</h1>
      <div class="meta">{{ $description }}</div>
      <hr>
    </div>

    <div id="showcase" class="doc-body row">
      <div class="doc-content col-md-9 col-12 order-1">
        <div class="content-inner">
          @each('word.content', $words, 'word')
        </div>

        {{ $words->links() }}
      </div><!--//doc-content-->
      <div class="doc-sidebar col-md-3 col-12 order-0 d-none d-md-flex">
        <div id="doc-nav" class="doc-nav">
          <nav id="doc-menu" class="nav doc-menu flex-column sticky">
            @foreach ($categories as $category)
              <a class="nav-link" href="{{ route('word.category', $category) }}">{{ $category->name }}</a>
            @endforeach
          </nav>
        </div>
      </div>
    </div>
  </div>
@endsection
