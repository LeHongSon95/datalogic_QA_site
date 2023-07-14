<form class="" action="{{ route('admin.setting.create_recommend') }}" method="POST">
    @csrf
    <div class="form-featured-keywords__group">
        <div class="form-group note">
            <div class="note__text">
                <select id="QaRecomanded" class="form-control" name="qa_recomended">
                    @foreach ($questions as $item)
                        <option value="{{ $item->id }}"
                            {{ ($item->questionRecommend || null) ? 'disabled' : '' }}>
                            {!! strip_tags($item->title) !!}</option>
                    @endforeach
                </select>
            </div>

            <div class="note__add text-start">
                <button id="btnCreateQaRecomanded" type="submit" class="btn btn-custom btn-delete"> {{ trans('messages.btn_create') }}</button>
            </div>
        </div>
        @if ($errors->has('qa_recomended'))
            <div class="error">{{ $errors->first('qa_recomended') }}</div>
        @endif
    </div>
</form>