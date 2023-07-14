require("jquery");

(function ($) {
    "use strict";

    $(document).ready(() => {
        /* Start back top */
        $(".back-top").on("click", function () {
            $("html").scrollTop(0);
        });

        // event change font size
        handleChangeFontSize();
    });

    // handle scroll event
    let timer_clear;
    $(window).scroll(function () {
        if (timer_clear) clearTimeout(timer_clear);

        timer_clear = setTimeout(function () {
            /* Start scroll back top */
            let $scrollTop = $(this).scrollTop();

            if ($scrollTop > 200) {
                $(".back-top").addClass("active");
            } else {
                $(".back-top").removeClass("active");
            }
        }, 100);
    });

    // handle change font size
    const handleChangeFontSize = () => {
        const body = $("body");
        const btnChangeFontSize = $(".btn-change-font-size");

        btnChangeFontSize.on("click", function () {
            const checkHasActive = $(this).hasClass("active");

            if (!checkHasActive) {
                const parent = $(this).closest(".option-change-font-size");
                const url = parent.data("url");
                const fontSize = $(this).data("font-size");

                // remove all class active
                parent.find(".btn-change-font-size").removeClass("active");
                // add class active btn click
                $(this).addClass("active");

                // ajax setting set font size
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    method: "get",
                    url: url,
                    dataType: "json",
                    data: {
                        type_font_size: fontSize,
                    },
                    success: function (result) {
                        if (result.status) {
                            window.location.reload();
                        }
                    },
                });
            }
        });
    };
})(jQuery);
