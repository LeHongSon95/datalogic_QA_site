(function ($) {
    "use strict";
    $(document).ready(() => {
        const handelFormSearch = () => {
            const inputKeyword = $("#key-words");
            const yua = $("#canvas");
        
            // show bnt clear keywords after load has key
            handelShowBtnClearKeywords(inputKeyword.val());

            // event replace string multi spec
            inputKeyword.on("keydown", function () {
                const valueInput = $(this).val().replace(/  +/g, " ");
                $(this).val(valueInput.trim());
            });

            // event show btn clear keywords
            inputKeyword.on("keyup", function () {
                const valueInput = $(this).val();

                handelShowBtnClearKeywords(valueInput);
            });

            // event remove all keyword
            $("#btn-clear-words").on("click", function () {
                inputKeyword.val("");
                handelShowBtnClearKeywords(inputKeyword.val());
            });
        };

        const handelShowBtnClearKeywords = (value) => {
            const btnClearWords = $("#btn-clear-words");
            if (value) {
                btnClearWords.addClass("active");
            } else {
                btnClearWords.removeClass("active");
            }
        };

        handelFormSearch();
    });
})(jQuery);
