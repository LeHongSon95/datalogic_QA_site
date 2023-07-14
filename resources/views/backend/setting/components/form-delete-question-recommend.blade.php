@if (count($questionsRecommendeds) > 0)
<form class="" action="{{ route('admin.setting.delete_recommend') }}" method="POST">
    @csrf
    <input type="hidden" name="recomanded_id" value="{{ old('recomanded_id') ?? '' }}">
    <div class="widget">
        <div class="widget__wrap">
            <div class="recomanded-list">
                <ul id="recomanded_list_container">
                    <?php
                    $i = 1;
                    ?>
                    @foreach ($questionsRecommendeds as $item)
                    @if ($i == 4)
                    @break
                    @endif
                    <li class="recomanded-item" data-class="recomanded-item" data-name="recomanded_id" data-id="{{ $item->id }}">
                        {!! $i . ' ' . strip_tags($item->title) !!}
                    </li>


                    <?php

                    $i++;
                    ?>
                    @endforeach
                </ul>
            </div>

            <div class="note__add text-start {{ count($questionsRecommendeds) > 2 ? 'mt-70' : 'my-4' }}">
                <button type="submit" class="btn btn-custom btn-add-question p-4"> {{ trans('messages.btn_delete') }}</button>
            </div>
        </div>
        @if ($errors->has('recomanded_id'))
        <div class="error" style="margin-left: 1.5rem">{{ $errors->first('recomanded_id') }}</div>
        @endif
    </div>
</form>
<script>
    let recomandedListContainer = document.getElementById('recomanded_list_container');
    if (recomandedListContainer.getElementsByTagName('LI').length >= 3) {
        document.getElementById('btnCreateQaRecomanded').disabled = true;
    }
</script>
@endif