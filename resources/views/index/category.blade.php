<div class="item item-orange col-lg-4 col-6">
    <div class="item-inner">
        <div class="icon-holder">
            <i class="icon icon_document"></i>
        </div><!--//icon-holder-->
        <h3 class="title">{{ $category->name }}</h3>
        <p class="intro">{{ $category->descrption }}</p>
        <a class="link" href="{{ route('word.category', $category) }}"></a>
    </div>
</div>
