<section id="{{ $term->slug }}" class="doc-section">
  @if (isset($index) and ($index % 5 == 0 and $index != 0))
    <div class="mb-5">
      @include('layouts.adsense.responsive')
    </div>
  @endif

  <div class="jumbotron">
    <h2>
      {{ $term->origin }}
      <br>
      <small><a href="{{ route('term.category', $term->category) }}"
                title="@lang('Bidang :category', ['category' => $term->category->name])">{{ $term->category->name }}</a></small>
    </h2>
    <h2>{{ $term->locale }}</h2>
    <hr>

    @php
      $tweet = __('Padanan istilah :origin (:category) adalah :locale.:line:link', [
        'origin' => $term->origin,
        'locale' => $term->locale,
        'category' => strtolower($term->category->name),
        'line' => str_repeat(PHP_EOL, 2),
        'link' => route('term.show', $term),
      ]);
    @endphp

    <div class="mt-4">
      @include('layouts.partials.tweet', compact('tweet', 'term'))
      <loveable link="{{ route('term.love', $term) }}" count="{{ $term->total_likes }}"></loveable>
      <reportable link="{{ route('term.report', $term) }}" total="{{ $term->reports_count }}"
                  auth="{{ auth()->check() }}"></reportable>
    </div>
  </div><!--//jumbotron-->
</section><!--//doc-section-->
