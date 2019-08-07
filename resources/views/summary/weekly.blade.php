@extends('layouts.app')

@push('metadata')
  <meta property="og:title" content="{{ $title }}" />
  <meta property="og:description" content="{{ $description ?? $title }}">
  <meta property="og:url" content="{{ url()->current() }}" />
@endpush

@section('content')
  <div class="container">
    <div id="doc-header" class="doc-header text-center"></div><!--//doc-header-->

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
          <h2>{{ $number->format($count['new_category']) }}</h2>
          <p>@lang('Bidang baru')</p>
          <hr>
          <h4>{{ $number->format($count['total_category']) }}</h4>
          <p>@lang('Total bidang')</p>
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

    <div class="row">
      <div class="col-md-12">
        <h3>@lang('Istilah Baru (:count)', ['count' => $number->format($words->count())])</h3>
        <table class="table">
          <thead>
          <tr>
            <th>@lang('Istilah')</th>
            <th>@lang('Padanan')</th>
            <th>@lang('Kontributor')</th>
          </tr>
          </thead>

          <tbody>
          @foreach($words as $word)
            <tr>
              <td><a href="{{ route('word.show', $word) }}">{{ $word->origin }}</a></td>
              <td>{{ $word->locale }}</td>
              <td>{{ data_get($word->user, 'name', __('Anonim')) }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
