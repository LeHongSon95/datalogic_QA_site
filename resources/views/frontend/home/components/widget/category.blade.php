<section class="widget widget-category">
    <h4 class="widget-title">
        {{ trans('messages.category') }}
    </h4>

    <div class="widget__body widget-category__warp">
        <ul class="list-group list-group-radio">

            <li class="list-group-item">
                <input id="cate-all" class="form-check-input check-cate" value="" type="radio" name="category"
                    {{ !request()->has('countQuestions') ? 'checked' : '' }} checked>
                <label for="cate-all" class="form-check-label form-check-label-cate">
                    <span class="form-check-label__cate">{{ trans('messages.all') }}</span>
                    <span class="question-total">({{ trans('messages.subject', ['count' => $countQuestions]) }})</span>
                </label>
            </li>

            @foreach ($categories as $category)
                <li class="list-group-item">
                    <input id="cate-{{ $category->id }}" class="form-check-input check-cate" value="{{ $category->id }}"
                        type="radio" name="category"
                        {{ request()->input('cate_id') == $category->id ? 'checked' : '' }}>
                    <label for="cate-{{ $category->id }}" class="form-check-label form-check-label-cate">
                        <span class="form-check-label__cate">{{ $category->title }}</span>
                        <span
                            class="question-total">({{ trans('messages.subject', ['count' => $category->question_count]) }})</span>
                    </label>
                </li>
            @endforeach
        </ul>
    </div>
</section>
