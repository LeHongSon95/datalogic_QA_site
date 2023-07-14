@if( !empty( $keywords ) && $setting && $setting->number_recently_viewed_keywords )
    <section class="widget widget-keywords">
        <h4 class="widget-title line">
            {{ trans('messages.recently_viewed_keywords') }}
        </h4>

        <div class="widget__body widget-keywords__warp">
            @foreach( $keywords as $keyword)
                @if( $loop->index == $setting->number_recently_viewed_keywords )
                    @break
                @endif

                <button type="button" class="btn btn-keywords">
                    {{ $keyword }}
                </button>
            @endforeach
        </div>
    </section>
@endif