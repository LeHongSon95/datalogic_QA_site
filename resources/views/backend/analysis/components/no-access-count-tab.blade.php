<div class="tab-content__chart">
    <h4>{{ $title }}</h4>
    <div class="tab-body">
        <div class="navbox">
            {{-- select order --}}
            <div class="nav-box__right">
                <select class="form-select select-order-qa" name="order_by" aria-label="">
                    @foreach( Config::get('constants.orders') as $order )
                    <option value="{{ $order }}" {{ request()->input('order_by') == $order ? 'selected': ''  }}>{{ trans('messages.order_' . $order) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="nav-box__left">
                <span>{{ trans('messages.display_number') }}</span>
                <select class="form-select select-items-per-page show-item" name="itemsPage" aria-label="">
                    @foreach( Config::get('constants.itemsPerPageQA') as $itemsPage )
                    <option value="{{ $itemsPage }}" {{ request()->input('itemsPage') == $itemsPage ? 'selected': ''  }}>{{ $itemsPage }}</option>
                    @endforeach
                </select>


            </div>
        </div>
        <div class="chart2">
            @if( count( $questions ) )
            <div class="list-qa">
                @foreach( $questions as $question )
                <div class="list-qa__item">
                    <p>
                        ① {{ $question->title }}
                    </p>
                    <p>
                        {{ $question->view_count }}
                    </p>

                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>

<div class="box">
    <h4>{{ $title }}</h4>
    <div class="">
        <select class="select" name="items_per_page" aria-label="">
            <option value="">
                ①アーチ梁につく小梁を、アーチ梁なりの高さで転ばせたい。
            </option>
        </select>
    </div>
</div>

<script>
    new SimpleBar(document.querySelector('.chart'));
</script>