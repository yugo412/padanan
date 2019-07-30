<div class="callout-block callout-info">
  <div class="icon-holder">
    <i class="fas fa-info-circle"></i>
  </div><!--//icon-holder-->
  <div class="content">
    @if (!empty($title))
      <h4 class="callout-title">{{ $title }}</h4>
    @endif

    <p>{{ $message }}</p>
  </div><!--//content-->
</div>
