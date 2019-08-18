@extends('layouts.app')

@push('metadata')
  <meta property="og:title" content="{{ $title }}"/>
  <meta property="og:description" content="{{ $description ?? $title }}">
  <meta property="og:url" content="{{ url()->current() }}"/>
@endpush

@section('content')
  <div class="container">
    <div id="doc-header" class="doc-header text-center"></div><!--//doc-header-->

    <div class="doc-body row">
      <div class="doc-content offset-lg-2 col-lg-8 col-12 order-1">

        @if (isset($contact))
          <h1>ada</h1>
        @endif

        @includeWhen(isset($contact), 'layouts.callouts.info', [
          'message' => __('Halo :name, terima kasih telah mengirim pesan kepada pengembang. Kami akan meresponns pesan Anda dengan segera.', [
            'name' => $contact->name ?? null,
          ])
        ])

        <div class="content-inner">
          <section id="license" class="doc-section">
            <h2 class="section-title">@lang('Kontak')</h2>
            <h6
              class="mt-2">@lang('Kirim pesan sebagai :user', ['user' => data_get(auth()->user(), 'name', 'Tamu')])</h6>
            <div class="section-block">
              <div class="jumbotron text-left">
                <form action="{{ route('contact') }}" method="post">
                  @csrf

                  <div class="form-group">
                    <label for="name">@lang('Nama lengkap')</label>
                    <input type="text" name="name" value="{{ old('name', data_get(auth()->user(), 'name')) }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="email">@lang('Alamat surel')</label>
                    <input type="email" name="email" value="{{ old('email', data_get(auth()->user(), 'email')) }}"
                           class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="message">@lang('Pesan')</label>
                    <textarea name="message" class="form-control @error('message') is-invalid @enderror"
                              rows="10">{{ old('message') }}</textarea>
                    @error('message')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <button class="btn btn-primary" type="submit">@lang('Kirim')</button>
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
