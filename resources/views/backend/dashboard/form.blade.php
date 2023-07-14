<form class="form-add-qa" action={{ $action }} method="POST">
    @if (isset($qa))
        @method('PUT')
        @php
            if (request()->clear == 1) {
                $qa = null;
            }
        @endphp
    @endif
    @csrf
    <div class="form-add-qa__group control-question">
        <label for="question"
            class="form-label mb-3 title">＜{{ trans('messages.dashboard.question') }}＞<span class="text-danger">*</span></label>
        <div class="note">
            <div class="note__text">
                {{ trans('messages.dashboard.question_note') }}
            </div>
            @if (!isset($qa))
                <div class="note__add text-end">
                    <button type="button" 
                        class="btn btn-custom btn-add-question">{{ trans('messages.btn_create') }}</button>
                </div>
            @endif
        </div>
        <div class="question-box">
            @php
                $questions = old('questions') ?? [$qa->title ?? ''] ?? [];
            @endphp
            
            @for ($i = 0; $i < 5; $i++)
                @php
                    $check = true;
                    if ($i <= (count($questions) - 1)) {
                        $check = false;
                    }
                @endphp
                <div class="question-item my-2 d-flex align-items-center {{ $check ? 'd-none' : '' }}">
                    <textarea id="question{{ $i }}" class="form-control" name="questions[]">{!! html_entity_decode($questions[$i] ?? '') !!}</textarea>
                    <button type="button" class="mx-2 btn p-0 d-flex align-items-center remove_question" @if($i == 0) style="opacity: 0;" @endif>
                        <span style="color: #F11B0B;" class="material-icons">remove_circle_outline</span>
                    </button>
                </div>
            @endfor

        </div>
        @if ($errors->has('questions'))
            <div class="error">{{ $errors->first('questions') }}</div>
        @endif
    </div>

    <div class="form-add-qa__group control-answer">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <label for="answer"
                class="form-label m-0 title answer">＜{{ trans('messages.dashboard.answer') }}＞<span class="text-danger">*</span></label>
            <div class="extract-answers">
                <button type="button" class="btn btn-extract-answers p-0 d-flex align-items-center" style="cursor:default">
                    <span class="material-icons me-2">find_in_page</span>
                    <span class="text">回答を抽出する</span>
                </button>
            </div>
        </div>

        <textarea id="answer" class="form-control answer editor" rows="10" name="answer">{!! old('answer') ?? html_entity_decode($qa->answers->content ?? '') ?? '' !!}</textarea>
        @if ($errors->has('answer'))
            <div class="error">{{ $errors->first('answer') }}</div>
        @endif
    </div>

    <div class="form-add-qa__group control-hashtag">
        <img class="icon" src={{ asset('images/icons/hash.svg') }} alt="hash">

        <label for="hashtag" class="form-label mb-3">{{ trans('messages.dashboard.tag') }}</label>
        <div class="note related-question" >
            <div class="note__text">
                <select id="hashtag" class="form-control" name="hashtag[]" multiple="multiple">
                    @php
                        $hashtag = old('hashtag') ?? (isset($qa->tags) ? $qa->tags->pluck('id')->toArray() : []) ?? [];
                    @endphp
                    @foreach ($tags as $item)
                        <option value="{{ $item->id }}"
                            {{ $hashtag && in_array($item->id, $hashtag) ? 'selected' : '' }}>{{ $item->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if ($errors->has('hashtag'))
            <div class="error">{{ $errors->first('hashtag') }}</div>
        @endif
    </div>

    <div class="form-add-qa__group control-status">
        <img class="icon" src={{ asset('images/icons/status.svg') }} alt="hash">

        <label class="form-label mb-3">{{ trans('messages.dashboard.status') }}</label>

        <div class="form-switch mb-3">
            <label class="form-check-label"
                for="switch-status">{{ trans('messages.dashboard.status_change') }}</label>
            @php
                $status = !old() || old('status') == 1 || isset($qa->status) == 1;
                if (isset($qa) && $qa->status === 0) {
                    $status = false;
                }
            @endphp
            <input @if($status) checked="checked" @endif class="form-check-input" type="checkbox" id="switch-status" name="status" value="1">
        </div>
    </div>

    <div class="form-add-qa__group control-folder">
        <img class="icon" src={{ asset('images/icons/folder.svg') }} alt="hash">

        <label class="form-label mb-3">フォルダ<span class="text-danger">*</span></label>

        <select class="form-control form-select" multiple id="categories" name="categories[]">
            @php
                $category = old('categories') ?? (isset($qa->categories) ? $qa->categories->pluck('id')->toArray() : []) ?? [];
            @endphp
            @foreach ($categories as $item)
                <option value={{ $item->id }}
                    {{ $category && in_array($item->id, $category) ? 'selected' : '' }}>
                    {{ $item->title }}</option>
            @endforeach
        </select>
        @if ($errors->has('categories'))
            <div class="error">{{ $errors->first('categories') }}</div>
        @endif
    </div>

    <div class="form-add-qa__group control-question control-related-question">
        <img class="icon" src={{ asset('images/icons/folder.svg') }} alt="hash">
        @php
            $related_question_input = [];
            $tag = [];
            if (isset($qa->questionRelateds)) {
                foreach ($qa->questionRelateds as $item) {
                    $tag[$item->question_related_id] = implode(',', $item->question->tags?->pluck('id')->toArray() ?? []);
                    $related_question_input[$item->question_related_id] = $item->question->title ?? '';
                }
            }
        @endphp
        <label class="form-label mb-3">{{ trans('messages.dashboard.related_question') }}</label>
        <div class="note related-question" >
            <div class="note__text">
                <select class="form-control form-select" id="relatedQuestion"
                    data-tag={{ old('data_tag') ?? json_encode($tag) ?? '' }}
                    data-disabled='{{ old('related_question_input') ?? json_encode($related_question_input) ?? '' }}'
                    data-url="{{ route('admin.question.get_list_by_tag') }}" name="related_question">

                </select>
            </div>
            <div class="note__add text-end">
                <button type="button" class="btn btn-custom btn-add-related-question">追加</button>
            </div>
        </div>
    </div>

    <div class="form-add-qa__group control-answer">
        <img class="icon" src={{ asset('images/icons/edit_note.svg') }} alt="answer">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <label for="note" class="form-label m-0">メモ</label>
        </div>
        <textarea id="note" class="form-control note editor" name="note">{{ old('note') ?? $qa->note ?? '' }}</textarea>
        @if ($errors->has('note'))
            <div class="error">{{ $errors->first('note') }}</div>
        @endif
    </div>

    <div class="form-add-qa__group control-action text-end">
        @if (isset($qa) || request()->clear == 1)
            <button type="button" class="btn btn-cancel me-3" id="btnClear">{{ trans('messages.btn_cancle') }}</button>
        @else
            <a href="{{ route('question.create') }}" class="btn btn-cancel me-3">{{ trans('messages.btn_cancle') }}</a>
        @endif
        <button type="button" id="btnSubmit"
            class="btn btn-custom btn-register">{{ trans('messages.btn_create_qa') }}</button>
    </div>
</form>

@section('addJs')
    <script src={{ asset('assets/js/libs/tinymce/tinymce.js') }}></script>
    <script src={{ asset('assets/js/pages/dashboard.js') }}></script>

    <script>
        tinymce.init({
            selector: '.answer.editor',
            height: 500,
            setup: function(editor) {
                editor.on('init change', function() {
                    editor.save();
                });
                editor.on('NodeChange', function(e) {
                    if (e && e.element.nodeName.toLowerCase() == 'img') {
                        tinyMCE.DOM.setAttribs(e.element, {'style': 'max-width: 600px; height: auto'});
                    }
                });
            },
            plugins: "advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table",
            toolbar: 'bold italic file link image media increaseFontSize decreaseFontSize forecolor removeformat',
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css',
                '/assets/css/custom/tinymce.css'
            ],
            menubar: false,
            statusbar: false,
            image_title: true,
            automatic_uploads: true,
            convert_urls: false,
            extended_valid_elements: 'a[href|target=_blank]',
            file_picker_types: 'file image media',
            link_class_list: [
            {title: 'Link', value: 'text-primary'},
            {title: 'File', value: 'text-blue'},
        ],
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.onchange = function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    // FormData
                    var fd = new FormData();
                    var files = file;
                    fd.append('filetype', meta.filetype);
                    fd.append("upload", files);
                    var filename = "";
                    // AJAX
                    var xhr, formData;
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '{{ route('upload') }}');
                    var token = document.head.querySelector("[name=csrf-token]").content;
                    xhr.setRequestHeader("X-CSRF-Token", token);
                    xhr.onload = function() {
                        var json;
                        if (xhr.status != 200) {
                            alert('HTTP Error: ' + xhr.status);
                            return;
                        }
                        json = JSON.parse(xhr.responseText);
                        if (!json || typeof json.location != 'string') {
                            alert('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                        filename = json.location;
                        name = json.name;
                        reader.onload = function(e) {
                            if (meta.filetype == 'file') {
                                cb(filename, {
                                    text: name,
                                    'css_classes_attribute': 'int_mark_link'
                                });

                            }

                            // Provide image and alt text for the image dialog
                            if (meta.filetype == 'image') {
                                cb(filename, {
                                    alt: name
                                });
                            }

                            // Provide alternative source and posted for the media dialog
                            if (meta.filetype == 'media') {
                                cb(filename);
                            }
                        };
                        reader.readAsDataURL(file);
                    };
                    xhr.send(fd);
                    return;
                };
                input.click();
            },
        });

        tinymce.init({
            selector: '#question0, #question1, #question2, #question3, #question4',
            height: 150,
            width: 750,
            setup: function(editor) {
                editor.on('init change', function() {
                    editor.save();
                });
                editor.on('NodeChange', function(e) {
                    if (e && e.element.nodeName.toLowerCase() == 'img') {
                        tinyMCE.DOM.setAttribs(e.element, {'style': 'max-width: 200px; height: auto'});
                    }
                });
            },
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css',
                '/assets/css/custom/tinymce.css'
            ],
            plugins: "image fullscreen",
            toolbar: 'image',
            menubar: false,
            statusbar: false,
            image_title: true,
            automatic_uploads: true,
            convert_urls: false,
            extended_valid_elements: 'a[href|target=_blank]',
            file_picker_types: 'image',
            smart_paste: false,
            paste_as_text: true,
            paste_block_drop: true,
            link_class_list: [
            {title: 'Link', value: 'text-primary'},
            {title: 'File', value: 'text-blue'},
        ],
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.onchange = function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    // FormData
                    var fd = new FormData();
                    var files = file;
                    fd.append('filetype', meta.filetype);
                    fd.append("upload", files);
                    var filename = "";
                    // AJAX
                    var xhr, formData;
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '{{ route('upload') }}');
                    var token = document.head.querySelector("[name=csrf-token]").content;
                    xhr.setRequestHeader("X-CSRF-Token", token);
                    xhr.onload = function() {
                        var json;
                        if (xhr.status != 200) {
                            alert('HTTP Error: ' + xhr.status);
                            return;
                        }
                        json = JSON.parse(xhr.responseText);
                        if (!json || typeof json.location != 'string') {
                            alert('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                        filename = json.location;
                        name = json.name;
                        reader.onload = function(e) {
                            // Provide image and alt text for the image dialog
                            if (meta.filetype == 'image') {
                                cb(filename, {
                                    alt: name
                                });
                            }
                        };
                        reader.readAsDataURL(file);
                    };
                    xhr.send(fd);
                    return;
                };
                input.click();
            },
        });

        tinymce.init({
            selector: '.note.editor',
            height: 200,
            setup: function(editor) {
                editor.on('init change', function() {
                    editor.save();
                });
            },
            toolbar: false,
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css',
                '/assets/css/custom/tinymce.css'
            ],
            menubar: false,
            statusbar: false,
            link_class_list: [
            {title: 'Link', value: 'text-primary'},
            {title: 'File', value: 'text-blue'},
        ]});

    </script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                text: '{{ session('success') }}',
                confirmButtonText: "はい",
            })
        </script>
    @endif
@endsection