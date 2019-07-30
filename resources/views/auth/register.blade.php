@extends('layouts.app')

@section('content')
  <div class="container">
    <div id="doc-header" class="doc-header text-center"></div><!--//doc-header-->

    <div class="doc-body row">
      <div class="doc-content offset-lg-2 col-lg-8 col-12 order-1">
        <div class="content-inner">
          <section id="license" class="doc-section">
            <h2 class="section-title">@lang('Daftar')</h2>
            <h6 class="mt-2">{{ $title }}</h6>
            <div class="section-block">
              <div class="jumbotron text-left">
                <form action="{{ route('register') }}" method="post">
                  @csrf

                  <div class="form-group">
                    <label for="email">@lang('Nama lengkap')</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="email">@lang('Alamat surel')</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="email">@lang('Sandilewat')</label>
                      <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                      @error('password')
                      <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-md-6">
                      <label for="email">@lang('Konfirmasi sandilewat')</label>
                      <input type="password" name="password_confirmation" class="form-control @error('password') is-invalid @enderror">
                    </div>
                  </div>

                  <div class="form-group">
                    <button class="btn btn-primary" type="submit">@lang('Daftar')</button>
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
