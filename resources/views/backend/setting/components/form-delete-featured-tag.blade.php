<section class="widget widget-keywords">
    <h4 class="widget-title line widget-title-featured">
        {{ trans('messages.featured_keywords') }}
    </h4>
    <form action="{{ route('admin.setting.delete_featured_tag') }}" method="post">
        @csrf
        <input type="hidden" name="featured_tag_id" value="{{ old('featured_tag_id') ?? '' }}">
        <div class="widget__wrap">
            <div class="widget__body widget-keywords__warp">
                <ul class="sort-keywords connected-sortable" data-url="{{ route('admin.setting.update_sort_featured_tag') }}">
                    @foreach ($featured_keywords as $item)
                        <li class="draggable-item" data-class="draggable-item"
                            data-name="featured_tag_id" data-id="{{ $item->id }}" data-title="{{ $item->title }}" data-featured="{{ $item->is_featured }}">
                            {{ $item->title }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="note__add text-start">
                <button type="submit" class="btn btn-custom btn-delete p-4">
                    {{ trans('messages.btn_delete') }}</button>
            </div>
        </div>
        @if ($errors->has('featured_tag_id'))
            <div class="error" style="margin-left: 2rem">{{ $errors->first('featured_tag_id') }}</div>
        @endif
    </form>
</section>
