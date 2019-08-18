@if (app()->environment('local'))
  <div class="text-center">
    <img src="{{ asset('images/ads-300x250.png') }}"
         alt="Adsense placeholder">
  </div>
@else
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <!-- padanan-responsive -->
  <ins class="adsbygoogle"
       style="display:block"
       data-ad-client="ca-pub-3097905359517350"
       data-ad-slot="3239828745"
       data-ad-format="auto"
       data-full-width-responsive="true"></ins>
  <script>
    (adsbygoogle = window.adsbygoogle || []).push({});
  </script>
@endif
