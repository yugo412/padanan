<a
  href="https://twitter.com/intent/tweet?via={{ str_replace('@', '', config('twitter.username')) }}&hashtags=padanan,glosarium&text={{ urlencode($tweet) }}"
  class="btn btn-outline-info btn-sm text-info" target="_blank"
  title="@lang('Bagikan :origin ke Twitter', ['origin' => $term->origin])"><i
    class="fab fa-twitter"></i> @lang('Bagikan')
</a>
