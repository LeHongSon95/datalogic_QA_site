<form class="form-search-qa {{ $class }}" method={{ $method }}>
    <div class="group-search">
        <div class="control-group">
            <input type="text" id="key-words" class="form-control"
                value="{{ \App\Helpers\Helper::pregReplaceSingleSpace(request()->input($name)) }}" name="{{ $name }}"
                aria-label="" autocomplete="off">
            <button id="btn-clear-words" type="button" class="btn-icon btn-clear-words material-icons">close</button>
            <button type="submit" class="btn-icon btn-submit-search material-icons">search</button>
        </div>
    </div>
</form>
