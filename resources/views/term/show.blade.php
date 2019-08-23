@extends('layouts.app')

@push('metadata')
  <meta property="og:title" content="{{ $title }}" />
  <meta property="og:description" content="{{ $description }}">
  <meta property="og:url" content="{{ \Illuminate\Support\Facades\Request::fullUrl() }}" />
@endpush

@section('content')
  <div class="container">
    <section class="adsense">
      @include('layouts.adsense.responsive')
    </section>

    <div id="doc-header" class="doc-header text-center"></div><!--//doc-header-->

    <div class="doc-body row">
      <div class="doc-content offset-md-2 col-md-8 col-xs-12 order-1">
        <div class="content-inner">
          <section id="license" class="doc-section">
            <h2 class="section-title">
              @if (!empty($term->category['icon']))
                <i class="fa fa-{{ $term->category['icon'] }} fa-fw text-info"></i>
              @endif
              {{ $term->category->name }}
            </h2>
            <div class="section-block">

              <div class="jumbotron text-left">
                <h2>{{ $term->origin }}</h2>
                <hr>
                <h2>{{ $term->locale }}</h2>
              </div>

              <h3>@lang('Deskripsi')</h3>
              <p>@lang('Tidak ada deskripsi untuk padanan kata ini.')</p>

              @php
                $tweet = __('Padanan istilah :origin (:category) adalah :locale.:line:link', [
                  'origin' => $term->origin,
                  'locale' => $term->locale,
                  'category' => strtolower($term->category->name),
                  'line' => str_repeat(PHP_EOL, 2),
                  'link' => route('term.show', $term),
                ]);
              @endphp

              @include('layouts.partials.tweet', compact('tweet', 'term'))
              <loveable link="{{ route('term.love', $term) }}" count="{{ $term->total_likes }}"></loveable>
              <reportable link="{{ route('term.report', $term) }}" total="{{ $term->reports_count }}"
                          auth="{{ auth()->check() }}"></reportable>
            </div>

          </section><!--//doc-section-->

          @include('layouts.partials.disqus', [
            'url' => url()->current(),
            'slug' => $term->slug,
          ])

        </div><!--//content-inner-->
      </div><!--//doc-content-->
    </div><!--//doc-body-->
  </div>
@endsection
