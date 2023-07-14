@php
    $fontSize =  \App\Helpers\Helper::getCookieSetFontSize()
@endphp
<div class="option-change-font-size d-flex align-items-center justify-content-lg-end {{ $class }}" data-url="{{ route('frontend.changeFontSize') }}">
    <span class="text me-4">{{ trans('messages.change_font_size') }}</span>

    <div class="action-group">
        @foreach( Config::get('constants.optionFontSize') as $item )
            <button type="button" class="btn btn-change-font-size {{ $fontSize['type'] === $item ? 'active' : '' }}" data-font-size="{{ $item }}">
                {{ trans( 'messages.change_font_size_'. $item ) }}
            </button>
        @endforeach
    </div>
</div>
