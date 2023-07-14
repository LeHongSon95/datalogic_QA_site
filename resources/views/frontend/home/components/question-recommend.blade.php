@if (!$isSearch && count($questionRecommend))
    <h4 class="top__left">
        {{ trans('messages.qa_recommend') }}
    </h4>
    <div class="list-qa mb-4">
        @foreach ($questionRecommend as $question)
            <div id="question-{{ $question->id }}" class="list-qa__item">
                <p class="cate">
                    {{ trans('messages.category') }} :
                    @if (count($question->categories))
                        @foreach ($question->categories as $category)
                            <span>{{ $category->title }}</span>
                        @endforeach
                    @endif
                </p>

                <h4 class="title">
                    {!! strip_tags($question->title) !!}
                </h4>

                <div class="content">
                    <div class="content__left">
                        @if (!empty($question->answers->content))
                        {!! html_entity_decode($question->answers->content) !!}
                        @endif

                        <a href="{{ route('frontend.qa.show', ['id' => $question->id]) }}" class="more">
                            … {{ trans('messages.show_detail') }}
                        </a>
                    </div>

                    <div class="content__right d-flex align-items-end">
                        <button type="button" 
                            class="btn-icon btn-add-favorite {{ in_array($question->id, $qaFavoriteCookies) ? 'active' : '' }}"
                            data-url="{{ route('frontend.qa.update_favorite', ['id' => $question->id]) }}"
                        ></button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
