@extends('layouts.app')

@section('content')
  <div class="container">
    <div id="doc-header" class="doc-header text-center">
      <h1 class="doc-title"><span aria-hidden="true" class="icon icon_datareport_alt"></span> {{ $category->name }}</h1>
      <div class="meta"><i class="far fa-clock"></i> @lang('Last updated :date', ['date' => $category->updated_at->diffForHumans()])</div>
    </div><!--//doc-header-->
    <div class="doc-body row">
      <div class="doc-content col-md-9 col-12 order-1">
        <div class="content-inner">
        </div><!--//content-inner-->
      </div><!--//doc-content-->
      <div class="doc-sidebar col-md-3 col-12 order-0 d-none d-md-flex">
        <div id="doc-nav" class="doc-nav">
          <nav id="doc-menu" class="nav doc-menu flex-column sticky">
          </nav><!--//doc-menu-->
        </div>
      </div><!--//doc-sidebar-->
    </div><!--//doc-body-->
  </div>
@endsection
