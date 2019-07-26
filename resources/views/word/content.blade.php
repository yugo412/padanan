<section id="{{ $word->slug }}" class="doc-section">
  <div class="jumbotron">
    <h2>
      {{ $word->origin }}<br>
      <small><a href="{{ route('word.category', $word->category) }}">{{ $word->category->name }}</a></small>
    </h2>
    <hr>
    <h2>{{ $word->locale }}</h2>
  </div><!--//jumbotron-->
</section><!--//doc-section-->
