@extends('layouts.app')

@section('content')
  <div class="container">
    <div id="doc-header" class="doc-header text-center">
      <h1 class="doc-title"><span aria-hidden="true" class="icon {{ $category->metadata['icon'] ?? 'icon_search-2' }}"></span> {{ $category->name }} ({{ $total }})</h1>
      <div class="meta">{{ $category->description }}</div>
    </div><!--//doc-header-->
    <div id="showcase" class="doc-body row">
      <div class="doc-content col-md-9 col-12 order-1">
        <div class="content-inner">
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
