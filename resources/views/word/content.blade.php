<section id="{{ $word->slug }}" class="doc-section">
  <div class="jumbotron">
    <h2>
      {{ $word->origin }}
      <br>
      <small><a href="{{ route('word.category', $word->category) }}">{{ $word->category->name }}</a></small>
    </h2>
    <h2>{{ $word->locale }}</h2>
    <hr>

    <div class="mt-4">
      <loveable link="{{ route('word.love', $word) }}" count="{{ $word->total_likes }}"></loveable>
      <reportable link="{{ route('word.report', $word) }}" total="{{ $word->reports_count }}" auth="{{ auth()->check() }}"></reportable>
    </div>
  </div><!--//jumbotron-->
</section><!--//doc-section-->
