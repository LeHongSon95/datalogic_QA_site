(function ($) {
    "use strict";
    $(document).ready(() => {
        var questionnaireItemActive = $(".questionnaire__item.active");
        let scroll = 0;
        if (questionnaireItemActive.length > 0) {
            scroll =
                $(".questionnaire__item.active").offset().top -
                $(".questionnaire__item.active").height();
        }
        $(".site-questionnaire__list").animate({ scrollTop: scroll }, 1000);
        $(".questionnaire__item").on("click", function () {
            $(".questionnaire__item").removeClass("active");
            $(this).addClass("active");
            let date = $(".site-questionnaire__content .date").clone();
            date.text($(this).find(".date").text());
            $(".site-questionnaire__content p").html(
                escapeHtml($(this).find(".content").text())
            );
            $(".site-questionnaire__content .title-header").text(
                escapeHtml($(this).find(".title").text())
            );
            $(".site-questionnaire__content .title-header").append(date);
        });
    });
})(jQuery);
