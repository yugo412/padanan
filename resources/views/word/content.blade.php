<section id="{{ $word->slug }}" class="doc-section">
  <div class="jumbotron">
    <h2>
      {{ $word->origin }}
      <a href="{{ route('word.show', [$word->category, $word]) }}" title="@lang('Lihat rincian')"><i class="fa fa-question-circle-o fa-fw"></i></a><br>
      <small><a href="{{ route('word.category', $word->category) }}">{{ $word->category->name }}</a></small>
    </h2>
    <h2>{{ $word->locale }}</h2>
    <hr>

    <div class="mt-4">
      <loveable link="{{ route('word.love', $word) }}" count="{{ $word->likes_count }}"></loveable>
    </div>
  </div><!--//jumbotron-->
</section><!--//doc-section-->
