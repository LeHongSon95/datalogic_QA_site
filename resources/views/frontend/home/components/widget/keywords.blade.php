@if( count( $keywords ) )
    <section class="widget widget-keywords">
        <h4 class="widget-title line">
            {{ trans('messages.featured_keywords') }}
        </h4>

        <div class="widget__body widget-keywords__warp">
            @foreach( $keywords as $keyword)
                <button id="canvas" type="button" class="btn btn-keywords">
                    {{ $keyword->title }}
                </button>
            @endforeach
        </div>
    </section>
@endif