@extends('layouts.app')

@section('content')
  <div class="container">
    <div id="doc-header" class="doc-header text-center"></div><!--//doc-header-->

    <div class="doc-body row">
      <div class="doc-content offset-md-2 col-md-8 col-xs-12 order-1">
        <div class="content-inner">
          <section id="license" class="doc-section">
            <h2 class="section-title">{{ $title }}</h2>
            <h6 class="mt-2">@lang('Tambah sebagai :name', [
              'name' => data_get(auth()->user(), 'name', __('Anonim'))
            ])</h6>
            <div class="section-block">

              @if(session('success'))
              <div class="callout-block callout-info">
                <div class="icon-holder">
                  <svg class="svg-inline--fa fa-info-circle fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path></svg><!-- <i class="fas fa-info-circle"></i> -->
                </div><!--//icon-holder-->
                <div class="content">
                  <h4 class="callout-title">@lang('Halo, :name!', ['name' => data_get(auth()->user(), 'name', 'orang asing')])</h4>
                  <p>@lang('Terima kasih telah menambahkan kata baru di <strong>:app</strong>. Bantuan Anda sangat berarti buat perkembangan aplikasi.', ['app' => config('app.name')])</p>
                </div><!--//content-->
              </div>
              @endif

              <div class="jumbotron text-left">
                <form action="{{ route('word.create') }}" method="post">
                  @csrf

                  <div class="form-group">
                    <label for="email">@lang('Bidang')</label>
                    <select name="category" class="form-control @error('category') is-invalid @enderror" id="category-select">
                      <option value=""></option>
                      @foreach ($categories as $category)
                        <option value="{{ $category->slug }}">{{ $category->name }}</option>
                      @endforeach
                    </select>
                    @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="email">@lang('Kata asing')</label>
                    <input type="text" name="origin" value="{{ old('origin') }}" class="form-control @error('origin') is-invalid @enderror" autocomplete="off">
                    @error('origin')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="email">@lang('Padanan kata (dalam bahasa Indonesia)')</label>
                    <input type="text" name="locale" class="form-control @error('locale') is-invalid @enderror" autocomplete="off">
                    @error('locale')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  @auth
                    <div class="form-group row">
                      <div class="col-md-12">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="tweet" id="remember" {{ old('tweet') ? 'checked' : '' }}>

                          <label class="form-check-label" for="remember">
                            @lang('Kirim istilah dan padanan baru ke Twitter <a href=":link" target="_blank">:twitter</a>', [
                              'twitter' => config('twitter.username'),
                              'link' => 'https://www.twitter.com/'.config('twitter.username'),
                             ])
                          </label>
                        </div>
                      </div>
                    </div>
                  @endauth

                  <div class="form-group">
                    <button class="btn btn-primary" type="submit">@lang('Tambah')</button>
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

@push('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
@endpush

@push('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
  <script>
    $(function(){
      $('#category-select').select2({
        placeholder: '@lang('Pilih salah satu bidang')',
        theme: 'bootstrap'
      })
    })
  </script>
@endpush
