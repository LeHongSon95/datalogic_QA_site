<div class="site-favorite-qa site-page">
  <div class="container">
    <h4>お気に入り</h4>
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

    <div class="favorite-list">
      @foreach ($data as $item)
      <div class="favorite-list__item">
        <div class="content">

          <div class="list-qa__item">
            <h4 class="title">
              @if (!empty($item->title))
              {!! html_entity_decode($item->title) !!}
              @endif
            </h4>
          </div>

        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
</div>
<script src="{{ asset('assets/js/pages/favorite.js') }}"></script>