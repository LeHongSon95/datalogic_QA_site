<div class="nav-bar">
    <div class="navigation-list">
        <a class="menu-item {{ Route::is('admin.FAQlist') || Route::is('question.*') ? 'active' : '' }}" 
            href="{{ route('admin.FAQlist') }}">{{ trans('messages.breadcrumb.backend.faq') }}</a>
        <a class="menu-item {{ Route::is('admin.analysis') ? 'active' : '' }}"
            href="{{ route('admin.analysis') }}">{{ trans('messages.breadcrumb.backend.analysis') }}</a>
        <a class="menu-item {{ Route::is('admin.setting') ? 'active' : '' }}"
            href="{{ route('admin.setting') }}">{{ trans('messages.breadcrumb.backend.setting') }}</a>
        <a class="menu-item {{ Route::is('admin.questionnaire.index') ? 'active' : '' }}"
            href="{{ route('admin.questionnaire.index') }}">{{ trans('messages.breadcrumb.backend.questionnaire') }}</a>
    </div>
</div>
