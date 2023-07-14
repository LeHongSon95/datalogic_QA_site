@foreach ($questionnaires as $item)
<div id="questionnaire-{{ $item->id }}" class="questionnaire__item {{ $loop->index == 0 ? 'active' : '' }}">
    <div class="content-wrapper">
        <div class="content-wrapper__left">
            <p class="title"> {!! strip_tags($item->question->title) !!}</p>
            <p class="date">{{ $item->created_at->format(config('date.date_format_y_m_d_h_m')) }}
            </p>
        </div>
        <p class="content">
            {!! $item->content !!}
        </p>
    </div>
</div>
@endforeach
