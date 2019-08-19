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
              @if (!empty($word->category['icon']))
                <i class="fa fa-{{ $worc->category['icon'] }} fa-fw text-info"></i>
              @endif
              {{ $word->category->name }}
            </h2>
            <div class="section-block">

              <div class="jumbotron text-left">
                <h2>{{ $word->origin }}</h2>
                <hr>
                <h2>{{ $word->locale }}</h2>
              </div>

              <h3>@lang('Deskripsi')</h3>
              <p>@lang('Tidak ada deskripsi untuk padanan kata ini.')</p>

              @php
                $tweet = __('Padanan istilah :origin (:category) adalah :locale.:line:link', [
                  'origin' => $word->origin,
                  'locale' => $word->locale,
                  'category' => strtolower($word->category->name),
                  'line' => str_repeat(PHP_EOL, 2),
                  'link' => route('word.show', $word),
                ]);
              @endphp

              <a href="https://twitter.com/intent/tweet?hashtags=padanan,glosarium&text={{ urlencode($tweet) }}"
                 class="btn btn-outline-info btn-sm text-info"><i class="fab fa-twitter"></i> @lang('Bagikan')</a>
              <loveable link="{{ route('word.love', $word) }}" count="{{ $word->total_likes }}"></loveable>
              <reportable link="{{ route('word.report', $word) }}" total="{{ $word->reports_count }}"
                          auth="{{ auth()->check() }}"></reportable>
            </div>

          </section><!--//doc-section-->

          @include('layouts.partials.disqus', [
            'url' => url()->current(),
            'slug' => $word->slug,
          ])

        </div><!--//content-inner-->
      </div><!--//doc-content-->
    </div><!--//doc-body-->
  </div>
@endsection
