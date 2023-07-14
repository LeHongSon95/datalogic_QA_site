<div class="tab-content__list">
    <h4>{{ $title }}</h4>
    <form class="form-search-qa" method="get" action="">
      <div class="nav-box has-paginator">
        <div class="nav-box__left">
          <select class="form-select select-items-per-page show-item" name="items_per_page" aria-label="">
            @foreach (Config::get('constants.itemsPerPageAnalysis') as $itemPerPage)
            <option value="{{ $itemPerPage }}" {{ request()->input('items_per_page') == $itemPerPage ? 'selected' : '' }}>
              {{ $itemPerPage }}
            </option>
            @endforeach
          </select>
        </div>
      </div>
    </form>
    <div class="list-qa mb-4">
    <div class="favorite-list">
      @foreach ($questionnaire as $item)
      <div class="favorite-list__item">
        <div class="content">

          <div class="list-qa__item">
            <h4 class="title">
              @if (!empty($item->content))
              {!! html_entity_decode($item->content) !!}
              @endif
            </h4>
          </div>
          
        </div>
      </div>
      @endforeach
    </div>
       
    </div>
</div>
