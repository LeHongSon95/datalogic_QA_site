@extends('layouts.master')
@section('title', 'Home')

@section('addCss')
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
<script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>

<!-- Font Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="./assets/css/libs/bootstrap.min.css">

<!-- Style CSS -->
<link rel="stylesheet" href="./assets/css/style.css">

<!-- Style Page CSS -->

<link rel="stylesheet" href="./assets/css/pages/dashboard.css">

@endsection

@section('content')

<title>DataLogic - Dashboard - Q&A追加</title>
<div class="sticky-footer">
    <div class="site-dashboard-qa site-page">
        <div class="container">
            <div class="warp-container">
                <h4 class="entry-heading under-line">
                    Q&A追加
                </h4>

                <form class="form-add-qa">
                    <div class="form-add-qa__group1">
                        <h4 class="title">Q</h4>
                        <div class="d-flex">
                            <div class="question-0">
                                <label for="question" class="mb-3" class="form-label mb-3">＜質問＞</label>
                                <div class="note">
                                    <div class="note__text">
                                        左の「追加ボタン」から、ユーザーが質問すると予想される文章を複数入力してください。
                                    </div>

                                    <div class="note__add text-end">
                                        <button type="button" class="btn btn-custom btn-add-question">追加</button>
                                    </div>
                                </div>

                                <input id="question" class="form-control" type="text" name="question" value="">
                            </div>
                        </div>

                    </div>

                    <div class="form-add-qa__group2">
                        <h4 class="title">A</h4>
                        <div class="flex-all">
                        <div class="dflex">
                            <label for="answer" class="form-label m2">＜回答＞</label>
                            <div class="d-flex align-items-center justify-content-between mb32">
                                <div class="extract-answers">
                                    <button type="button" class="btn btn-extract-answers p-0 d-flex align-items-center">
                                        <span class="material-icons me-2">find_in_page</span>
                                        <span class="text">回答を抽出する</span>
                                    </button>
                                </div>
                            </div>
                        
                    
                        </div>
                        <textarea id="editor" class="form-control answer" name="answer"></textarea>
                        </div>
                    </div>
                    <div class="form-add-qa__group control-hashtag">
                        <img class="icon" src="{{ asset('images/icons/hash.svg')}}" alt="hash">

                        <label for="hashtag" class="mb-3" class="form-label mb-3">ハッシュタグ</label>

                        <select id="hashtag" class="form-control" name="hashtag[]" multiple="multiple">

                            <option value="1">カテゴリー 1</option>

                            <option value="2">カテゴリー 2</option>

                            <option value="3">カテゴリー 3</option>

                            <option value="4">カテゴリー 4</option>

                            <option value="5">カテゴリー 5</option>

                            <option value="6">カテゴリー 6</option>

                            <option value="7">カテゴリー 7</option>

                            <option value="8">カテゴリー 8</option>

                            <option value="9">カテゴリー 9</option>

                            <option value="10">カテゴリー 10</option>

                        </select>
                    </div>

                    <div class="form-add-qa__group control-status">
                        <img class="icon" src="{{ asset('images/icons/status.svg')}}" alt="hash">

                        <label class="form-label mb-3">ステータス</label>

                        <div class="mb-2" class="form-switch mb-3">
                            <label class="form-check-label" for="switch-status">直ちにステータスを変更（ON/OFF)</label>
                            <input class="form-check-input" type="checkbox" id="switch-status" name="status" checked>
                        </div>

                        <div class="grid-control">
                            <div class="item">
                                <label for="status-immediately-1" class="form-label mb-2">直ちにステータスを変更（ON)</label>
                                <input id="status-immediately-1" class="form-control status-immediately" type="text">
                            </div>

                            <div class="item">
                                <label for="status-immediately-2" class="form-label mb-2">直ちにステータスを変更（ON)</label>
                                <input id="status-immediately-2" class="form-control status-immediately" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="form-add-qa__group control-folder">
                        <img class="icon" src="{{ asset('images/icons/folder.svg')}}" alt="hash">

                        <label class="form-label mb-3">ステータス</label>

                        <select class="form-control form-select">

                            <option value="1">カテゴリー 1</option>

                            <option value="2">カテゴリー 2</option>

                            <option value="3">カテゴリー 3</option>

                            <option value="4">カテゴリー 4</option>

                            <option value="5">カテゴリー 5</option>

                            <option value="6">カテゴリー 6</option>

                            <option value="7">カテゴリー 7</option>

                            <option value="8">カテゴリー 8</option>

                            <option value="9">カテゴリー 9</option>

                            <option value="10">カテゴリー 10</option>

                        </select>
                    </div>

                    <div class="form-add-qa__group control-answer">
                        <img class="icon" src="{{ asset('images/icons/edit_note.svg')}}" alt="answer">

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <label for="answer" class="form-label m-0">メモ</label>

                            <div class="extract-answers">
                                <button type="button" class="btn btn-extract-answers p-0 d-flex align-items-center">
                                    <span class="material-icons me-2"></span>
                                    <span class="text">回答を抽出する</span>
                                </button>
                            </div>
                        </div>

                        <textarea id="editor1" class="form-control note" name="note"></textarea>
                    </div>

                    <div class="form-add-qa__group control-action text-end">
                        <button type="button" class="btn btn-cancel me-3">キャンセル</button>
                        <button type="submit" class="btn btn-custom btn-register">登録する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    ClassicEditor.create(document.querySelector('#editor'), {
        toolbar: [
            'heading',
            '|',
            'bold',
            'italic',
            'uploadImage',
            '|',
            'bulletedList',
            'numberedList',
            '|',
            'outdent',
            'indent',
            '|',
            'undo',
            'redo',
            '|',
            'link',
            'blockQuote',
            'insertTable',
            'mediaEmbed',
        ],
        heading: {
            options: [{
                    model: 'paragraph',
                    title: 'Paragraph',
                    class: 'ck-heading_paragraph'
                },
                {
                    model: 'heading1',
                    view: 'h1',
                    title: 'Heading 1',
                    class: 'ck-heading_heading1',
                },
                {
                    model: 'heading2',
                    view: 'h2',
                    title: 'Heading 2',
                    class: 'ck-heading_heading2',
                },
                {
                    model: 'heading3',
                    view: 'h3',
                    title: 'Heading 3',
                    class: 'ck-heading_heading3',
                },
                {
                    model: 'heading4',
                    view: 'h4',
                    title: 'Heading 4',
                    class: 'ck-heading_heading4',
                },
                {
                    model: 'heading5',
                    view: 'h5',
                    title: 'Heading 5',
                    class: 'ck-heading_heading5',
                },
                {
                    model: 'heading6',
                    view: 'h6',
                    title: 'Heading 6',
                    class: 'ck-heading_heading6',
                },
            ],
        },
    }).catch((error) => {
        console.log(error);
    });
</script>
<script>
    ClassicEditor.create(document.querySelector('#editor1'), {
        toolbar: [
            'heading',
            '|',
            'bold',
            'italic',
            'uploadImage',
            '|',
            'bulletedList',
            'numberedList',
            '|',
            'outdent',
            'indent',
            '|',
            'undo',
            'redo',
            '|',
            'link',
            'blockQuote',
            'insertTable',
            'mediaEmbed',
        ],
        heading: {
            options: [{
                    model: 'paragraph',
                    title: 'Paragraph',
                    class: 'ck-heading_paragraph'
                },
                {
                    model: 'heading1',
                    view: 'h1',
                    title: 'Heading 1',
                    class: 'ck-heading_heading1',
                },
                {
                    model: 'heading2',
                    view: 'h2',
                    title: 'Heading 2',
                    class: 'ck-heading_heading2',
                },
                {
                    model: 'heading3',
                    view: 'h3',
                    title: 'Heading 3',
                    class: 'ck-heading_heading3',
                },
                {
                    model: 'heading4',
                    view: 'h4',
                    title: 'Heading 4',
                    class: 'ck-heading_heading4',
                },
                {
                    model: 'heading5',
                    view: 'h5',
                    title: 'Heading 5',
                    class: 'ck-heading_heading5',
                },
                {
                    model: 'heading6',
                    view: 'h6',
                    title: 'Heading 6',
                    class: 'ck-heading_heading6',
                },
            ],
        },
    }).catch((error) => {
        console.log(error);
    });
</script>
<!-- jquery -->
<script src="./assets/js/libs/jquery.min.js"></script>

<!-- Bootstrap Js -->
<script src="./assets/js/libs/bootstrap.bundle.min.js"></script>

<!-- General Js -->
<script src="./assets/js/pages/general.min.js"></script>
<script src="./assets/js/libs/tinymce-jquery.min.js"></script>
<script src="./assets/js/libs/select2.min.js"></script>
<script src="./assets/js/pages/dashboard.min.js"></script>
@endsection