<div class="widget widget-favorite">
    <a class="widget-favorite__link" href="{{ route('frontend.favorite') }}">
        <span class="material-icons">star</span>
        <span class="text favorite-count">
            {{ trans('messages.breadcrumb.frontend.favorite') }}
            ({{ trans('messages.subject', ['count' => count($qaFavoriteCookies)]) }})
        </span>
    </a>
</div>