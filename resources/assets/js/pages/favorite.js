(function ($) {
    "use strict";

    $(document).ready(() => {
        $('.select-items-per-page, .select-order-qa, .select-items-per-page_2').on('change', function () {
            $(this).parents('form').submit();
        })
    });
})(jQuery);
