@extends('layouts.app')

@push('metadata')
  <meta property="og:title" content="{{ $title }}" />
  <meta property="og:description" content="{{ $description ?? $title }}">
  <meta property="og:url" content="{{ url()->current() }}" />
@endpush

@section('content')
  <div class="container">

    @if (Agent::isDesktop())
      <div class="row">
        <div class="col-md-12">
          @include('layouts.adsense.responsive')
        </div>
      </div>
    @endif

    <div class="doc-body row">
      <div class="doc-content col-12 order-1">
        <div class="content-inner">
          <section id="weekly-summary" class="doc-section">
            <h2 class="section-title">@lang('Ringkasan Mingguan')</h2>
            <h6 class="mt-2">@lang(':day_start :week_start sampai :day_end :week_end', [
              'day_start' => $start->format('d'),
              'week_start' => $start->monthName,
              'day_end' => $end->format('d'),
              'week_end' => $end->monthName,
            ])</h6>
          </section>
        </div>
      </div>
    </div>

    <div class="row">

      <div class="col-md-6 col-sm-12">
        <div class="jumbotron text-right">
          <h2>{{ $number->format($count['new_word']) }}</h2>
          <p>@lang('Istilah baru')</p>
          <hr>
          <h4>{{ $number->format($count['total_word'])  }}</h4>
          <p>@lang('Total istilah')</p>
        </div>
      </div>

      <div class="col-md-6 col-sm-12">
        <div class="jumbotron text-right">
          <h2>{{ $number->format($count['new_report']) }}</h2>
          <p>@lang('Laporan kesalahan baru')</p>
          <hr>
          <h4>{{ $number->format($count['total_report']) }}</h4>
          <p>@lang('Total laporan')</p>
        </div>
      </div>

      <div class="col-md-6 col-sm-12">
        <div class="jumbotron text-right">
          <h2>{{ $number->format($count['new_search']) }}</h2>
          <p>@lang('Kueri pencarian')</p>
          <hr>
          <h4>{{ $number->format($count['total_search']) }}</h4>
          <p>@lang('Total pencarian')</p>
        </div>
      </div>

      <div class="col-md-6 col-sm-12">
        <div class="jumbotron text-right">
          <h2>{{ $number->format($count['new_user']) }}</h2>
          <p>@lang('Kontributor baru')</p>
          <hr>
          <h4>{{ $number->format($count['total_user']) }}</h4>
          <p>@lang('Total kontributor')</p>
        </div>
      </div>

    </div>

    @guest
      <div class="row mb-4">
        <div class="col-md-12">
          @include('layouts.adsense.responsive')
        </div>
      </div>
    @endguest

    <div class="row">
      <div class="col-md-12">
        <h3>@lang('Istilah Baru (:count)', ['count' => $number->format($terms->count())])</h3>
        <table class="table">
          <thead>
          <tr>
            <th>@lang('Istilah')</th>
            <th>@lang('Padanan')</th>
            <th>@lang('Bidang')</th>
          </tr>
          </thead>

          <tbody>
          @foreach($terms as $term)
            <tr>
              <td><a href="{{ route('term.show', $term) }}">{{ $term->origin }}</a></td>
              <td>{{ $term->locale }}</td>
              <td><a href="{{ route('term.category', $term->category) }}"
                     title="{{ $term->category->name }}">{{ $term->category->name }}</a></td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
