<div class="site-questionnaire__content">
    <h4 class="entry-heading under-line title-header position-relative">
        {{ strip_tags($questionnaires[0]->question->title) }}

        <span class="date">{{ $questionnaires[0]->created_at->format(config('date.date_format_y_m_d_h_m')) }} </span>
    </h4>
    <div id="preview">
        <p>
            {!! nl2br(e(strip_tags($questionnaires[0]->content))) !!}
        </p>
    </div>
</div>
