(function ($) {
    "use strict";

    $(document).ready(() => {
        $(document).on("change", "#customFile", function (param) {
            $(".loader").css({
                display: "flex",
                "justify-content": "center",
                "align-items": "center",
            });
            $(this).parents("form").submit();
        });
    });
})(jQuery);
