<section id="{{ $word->slug }}" class="doc-section">
  @if (isset($index) and ($index % 5 == 0 and $index != 0))
    <div class="mb-5">
      @include('layouts.adsense.responsive')
    </div>
  @endif

  <div class="jumbotron">
    <h2>
      {{ $word->origin }}
      <br>
      <small><a href="{{ route('word.category', $word->category) }}">{{ $word->category->name }}</a></small>
    </h2>
    <h2>{{ $word->locale }}</h2>
    <hr>

    @php
      $tweet = __(':twitter Padanan istilah :origin (:category) adalah :locale.:line:link', [
        'origin' => $word->origin,
        'locale' => $word->locale,
        'category' => strtolower($word->category->name),
        'line' => str_repeat(PHP_EOL, 2),
        'link' => route('word.show', $word),
        'twitter' => config('twitter.username'),
      ]);
    @endphp

    <div class="mt-4">
      <a href="https://twitter.com/intent/tweet?hashtags=padanan,glosarium&text={{ urlencode($tweet) }}"
         class="btn btn-outline-info btn-sm text-info"><i class="fab fa-twitter"></i> @lang('Bagikan')</a>
      <loveable link="{{ route('word.love', $word) }}" count="{{ $word->total_likes }}"></loveable>
      <reportable link="{{ route('word.report', $word) }}" total="{{ $word->reports_count }}" auth="{{ auth()->check() }}"></reportable>
    </div>
  </div><!--//jumbotron-->
</section><!--//doc-section-->
