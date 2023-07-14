<form class="" action="{{ route('admin.setting.create_featured_tag') }}" method="POST">
    @csrf
    <div class="form-featured-keywords__group">
        <div class="form-group note">
            <div class="note__text">
                <input 
                    id="featuredTagName" 
                    class="form-control" 
                    type="text" 
                    name="featured_tag_name" 
                    value="{{ old('featured_tag_name') ?? '' }}"
                >
                
            </div>

            <div class="note__add text-start">
                <button type="submit" class="btn btn-custom btn-add-question"> {{ trans('messages.btn_create') }}</button>
            </div>
        </div>
        @if ($errors->has('featured_tag_name'))
            <div class="error">{{ $errors->first('featured_tag_name') }}</div>
        @endif
    </div>
</form>