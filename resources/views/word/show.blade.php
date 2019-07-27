@extends('layouts.app')

@push('metadata')
  <meta property="og:title" content="{{ $title }}" />
  <meta property="og:description" content="{{ $description }}">
  <meta property="og:url" content="{{ \Illuminate\Support\Facades\Request::fullUrl() }}" />
@endpush

@section('content')
  <div class="container">
    <div id="doc-header" class="doc-header text-center"></div><!--//doc-header-->

    <div class="doc-body row">
      <div class="doc-content offset-md-2 col-md-8 col-xs-12 order-1">
        <div class="content-inner">
          <section id="license" class="doc-section">
            <h2 class="section-title">{{ $category->name }}</h2>
            <div class="section-block">

              <div class="jumbotron text-left">
                  <h2>{{ $word->origin }}</h2>
                  <hr>
                  <h2>{{ $word->locale }}</h2>
              </div>

              <h3>@lang('Deskripsi')</h3>
              <p>@lang('Tidak ada deskripsi untuk padanan kata ini.')</p>

              <loveable link="{{ route('word.love', $word) }}" count="{{ $word->likes_count }}"></loveable>
            </div>

          </section><!--//doc-section-->

        </div><!--//content-inner-->
      </div><!--//doc-content-->
    </div><!--//doc-body-->
  </div>
@endsection
