@extends('layouts.app')

@section('content')
  <div class="container">
    <div id="doc-header" class="doc-header text-center">
      <h1 class="doc-title"><span aria-hidden="true" class="icon icon_question"></span> {{ $title }}</h1>
      <hr>
    </div><!--//doc-header-->
    <div class="doc-body row">
      <div class="doc-content col-md-9 col-12 order-1">
        <div class="content-inner">

          <section id="about" class="doc-section">
            <h2 class="section-title">@lang('Apa itu :app?', ['app' => config('app.name')])</h2>
            <div class="section-block">
              <div class="jumbotron text-left">
                <h1 class="text-center">CC BY 3.0</h1>
                <p><strong>{{ config('app.name') }}</strong> merupakan aplikasi berbasis web yang menyediakan daftar istilah asing dalam bahasa Indonesia. Dengan nama lain, disebut juga sebagai glosarium.</p>
                <p>Sebelumnya aplikasi web {{ config('app.name') }} beralamat di <a href="https://glosarium.web.id">glosarium.web.id</a>.</p>

                <p>Glosarium adalah suatu daftar alfabetis istilah dalam suatu ranah pengetahuan tertentu yang dilengkapi dengan definisi untuk istilah-istilah tersebut. Biasanya glosarium ada di bagian akhir suatu buku dan menyertakan istilah-istilah dalam buku tersebut yang baru diperkenalkan atau paling tidak, tak umum ditemukan. Glosarium dwibahasa adalah daftar istilah dalam satu bahasa yang didefinisikan dalam bahasa lain atau diberi sinonim (atau paling tidak sinonim terdekat) dalam bahasa lain.</p>
                <p>Dalam pengertian yang lebih umum, suatu glosarium berisi penjelasan konsep-konsep yang relevan dengan bidang ilmu atau kegiatan tertentu. Dalam pengertian ini, glosarium terkait dengan ontologi. glorasium juga dapat dikatakan sebagai daftar bentuk abjad yang terangkum dalam sebuah buku makalah dll yang memiliki arti dan kadang daftarnya sesuai urutan abjad biasanya juga sering ditemukan di akhir halaman.Glosarium sangat membantu untuk menemukan arti dari kata-kata yang sulit.</p>

                <div class="list list-inline text-center">
                  <a class="btn btn-cta btn-orange" href="https://twitter.com/padanan_id" target="_blank"><i class="fab fa-twitter"></i> @lang('Ikuti :twitter di Twitter', ['twitter' => '@padanan_id'])</a>
                </div>
              </div><!--//jumbotron-->
            </div><!--//section-block-->

          </section><!--//doc-section-->
          <section id="credits" class="doc-section">
            <h2 class="section-title">@lang('Kredit')</h2>
            <div class="section-block">
              <ul class="list list-unstyled">

                <li><a href="https://id.wikipedia.org/wiki/Glosarium" target="_blank"><i class="fas fa-external-link-square-alt"></i> Glosarium (Wikipedia)</a></li>
                <li><a href="https://themes.3rdwavemedia.com/" target="_blank"><i class="fas fa-external-link-square-alt"></i> PrettyDocs HTML Template</a></li>
                <li><a href="http://118.98.223.79/glosarium/" target="_blank"><i class="fas fa-external-link-square-alt"></i> Glosarium (Pusah Bahasa)</a></li>
                <li><a href="https://kateglo.com/" target="_blank"><i class="fas fa-external-link-square-alt"></i> Kateglo ~ Kamus, tesaurus, dan glosarium bahasa Indonesia</a></li>

              </ul>
            </div><!--//section-block-->

          </section><!--//doc-section-->


        </div><!--//content-inner-->
      </div><!--//doc-content-->
      <div class="doc-sidebar col-md-3 col-12 order-0 d-none d-md-flex">
        <div id="doc-nav" class="doc-nav">
          <nav id="doc-menu" class="nav doc-menu flex-column sticky">
            <a class="nav-link scrollto" href="#about">@lang('Apa itu :app?', ['app' => config('app.name')])</a>
            <a class="nav-link scrollto" href="#credits">@lang('Kredit')</a>
          </nav><!--//doc-menu-->
        </div>
      </div><!--//doc-sidebar-->
    </div><!--//doc-body-->
  </div>
@endsection
