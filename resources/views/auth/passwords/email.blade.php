@extends('layouts.app')

@section('content')
  <div class="container">
    <div id="doc-header" class="doc-header text-center"></div><!--//doc-header-->

    <div class="doc-body row">
      <div class="doc-content offset-lg-2 col-lg-8 col-12 order-1">

        @includeWhen(session('status'), 'layouts.callouts.info', [
          'title' => __('Berhasil!'),
          'message' => session('status'),
        ])

        <div class="content-inner">
          <section id="license" class="doc-section">
            <h2 class="section-title">@lang('Lupa Sandilewat')</h2>
            <div class="section-block">
              <div class="jumbotron text-left">
                <form action="{{ route('password.email') }}" method="post">
                  @csrf

                  <div class="form-group">
                    <label for="email">@lang('Alamat surel')</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <button class="btn btn-primary" type="submit">@lang('Kirim Instruksi')</button>
                  </div>

                </form>
              </div><!--//jumbotron-->
            </div><!--//section-block-->

          </section><!--//doc-section-->

        </div><!--//content-inner-->
      </div><!--//doc-content-->
    </div><!--//doc-body-->
  </div>
@endsection
