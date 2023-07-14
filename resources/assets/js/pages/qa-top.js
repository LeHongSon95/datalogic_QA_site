(function ($) {
    "use strict";

    $(document).ready(() => {
        // Event form search qa
        handelFormSearchQA();

        // event click favourite
        $(document).on("click", ".btn-add-favorite", function () {
            $(this).toggleClass("active");
        });

        // Enable tooltips
        const tooltipTriggerList = document.querySelectorAll(
            '[data-bs-toggle="tooltip"]'
        );
        const tooltipList = [...tooltipTriggerList].map(
            (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
        );

        // Search highlight
        handelSearchHighlight();
        // Handle change search highlight
        $(".homepage-search-checkbox-custom").each(function (index) {
            this.checked && handleChangeSearchHighlight($(this).attr("name"));
        });

        $(".homepage-search-checkbox-custom").on("click", function () {
            if (this.checked) {
                $(".homepage-search-checkbox-custom")
                    .not(this)
                    .prop("checked", false);
                handleChangeSearchHighlight($(this).attr("name"));
            }
        });
    });

    // function submit form search QA
    const summitFormSearchQA = () => {
        $(".form-search-qa").submit();
    };

    // get value input key search
    const getValueInputKey = (element) => {
        let val = $.trim(element.val());

        if (val) {
            val = val.split(" ");
        }

        return val;
    };

    // event form search
    const handelFormSearchQA = () => {
        const inputKeyword = $("#key-words");

        // show bnt clear keywords after load has key
        handelShowBtnClearKeywords(inputKeyword.val());

        // event replace string multi spec
        inputKeyword.on("keydown", function () {
            const valueInput = $(this).val().replace(/  +/g, " ");

            $(this).val(valueInput);
        });
        $(".btn-keywords").each(function (index) {
            $(this).on("click", function () {
                handelShowBtnClearKeywords("button");
            });
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

        // event click check cate radio sidebar
        const checkLabelCate = $(".form-check-label-cate");
        const labelName = $(".key-search__name");
        const inputIdCate = $(".key-search__cate");
        const valInputIdCate = inputIdCate.val();
        const inputCateName = $(".cate-name-check");

        checkLabelCate.on("click", function () {
            const parent = $(this).closest(".list-group-item");
            const idCate = parent.find(".form-check-input").val();
            const nameCate = parent.find(".form-check-label__cate").text();

            labelName.text(nameCate);
            inputIdCate.val(idCate);
            inputCateName.val(nameCate);

            $("#key-search-check").removeClass("d-none");

            // summit form search
            summitFormSearchQA();
        });

        // add text cate after when search
        if (valInputIdCate) {
            const textCateCheck = $(
                `.form-check-label-cate[for=cate-${valInputIdCate}]`
            )
                .find(".form-check-label__cate")
                .text();

            labelName.text(textCateCheck);
            $("#key-search-check").removeClass("d-none");
        }

        // delete search filter cate
        $(".btn-delete-filter-cate").on("click", function () {
            const parentKeySearchCate = $(this).closest("#key-search-check");

            parentKeySearchCate.addClass("d-none");
            parentKeySearchCate.find(".key-search__cate").val("");

            // summit form search
            summitFormSearchQA();
        });

        // submit when change select
        $(".select-items-per-page, .select-order-qa").on("change", function () {
            summitFormSearchQA();
        });

        // event click list keyword history
        $(".search-history-list__item").on("click", function () {
            const newKeyword = $(this).text().trim();

            handelPushValueKeywordsSearch(newKeyword);
            handelShowBtnClearKeywords(newKeyword);
        });

        // event click keyword sidebar
        const btnKeywordSidebar = $(".btn-keywords");
        btnKeywordSidebar.on("click", function () {
            const parent = $(this).closest(".widget-keywords__warp");

            // active btn keyword
            parent.find(".btn-keywords").removeClass("active");
            $(this).addClass("active");

            // push value input keyword search
            const newKeyword = $(this).text().trim();
            handelPushValueKeywordsSearch(newKeyword);
        });
    };

    // handel show btn clear keywords

    const handelShowBtnClearKeywords = (value) => {
        const btnClearWords = $("#btn-clear-words");
        if (value) {
            btnClearWords.addClass("active");
        } else {
            btnClearWords.removeClass("active");
        }
    };

    // event push value input keyword search
    const handelPushValueKeywordsSearch = (newKeyword) => {
        const inputKeyWords = $("#key-words");
        let newKeywordArr = [];
        let newValueInputKeyWords = [];
        const oldValueInputKeyWords = getValueInputKey(inputKeyWords);

        if (newKeyword) {
            newKeywordArr = newKeyword.split(" ");

            if (oldValueInputKeyWords !== "") {
                newValueInputKeyWords =
                    oldValueInputKeyWords.concat(newKeywordArr);
            } else {
                newValueInputKeyWords = newKeywordArr;
            }

            inputKeyWords.val(newValueInputKeyWords.join(" ")).focus();
        }

        return true;
    };

    function escapeHtml(unsafe)
    {
        return unsafe
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
     }
    const arrayWordHighlight = () => {
        const searchWord = $("#key-words").val();
        let arrayWordNew = [];
        const isSpecialChars = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/.test(
            searchWord
        );
        if (searchWord) {
            let arrayWord = searchWord.split(/[|\s_]+/);

            arrayWordNew = arrayWord.filter(function (el) {
                return el !== null && el !== "";
            });
        }
 
        return isSpecialChars ? escapeHtml(searchWord).split() : arrayWordNew;
    };

    // event search highlight
    const handelSearchHighlight = (tagName) => {
        const arrayWordNew = arrayWordHighlight();
        // replace text show highlight
        if (arrayWordNew.length) {
            arrayWordNew.forEach(function (item) {
                const isSpecialChars = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/.test(item);
                const wordFilter =
                    !isSpecialChars &&
                    new RegExp(item, "ig");
                const repStr = "<mark class='highlight'>" + item + "</mark>";

                $(".list-qa__item").each(function () {
                   isSpecialChars ?  $(this).html($(this).html().replaceAll( item, repStr)) :  $(this).html($(this).html().replace( wordFilter, repStr))
                });
            });
        }
        return true;
    };

    $(document).on("click", ".btn-add-favorite", function () {
        let url = $(this).data("url");
        updateFavorite(url);
    });

    $(".list-qa__item .content video").removeAttr("controls");

    var updateFavorite = function (url) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            url: url,
            type: "POST",
            success: function (res, sec) {
                $(".favorite-count").text(
                    "お気に入りのQ&A(" + res.qa_favorite + "件)"
                );
            },
            error: function (res, sec) {},
        });
    };
    const handleChangeSearchHighlight = (tagName) => {
        const arrayWordNew = arrayWordHighlight();

        // replace text show highlight
        if (arrayWordNew.length) {
            arrayWordNew.forEach(function (item) {
                const repStr = "<mark class='highlight'>" + item + "</mark>";
                if (tagName == "title_search") {
                    $(".list-qa .content .content__left")
                        .find("mark")
                        .removeClass("highlight")
                        .replaceWith(`<span>${item}</span>`);
                } else if (tagName == "full_text_search") {
                    $(".list-qa .content .content__left")
                        .find("span")
                        .replaceWith(repStr);
                }
            });
        }
    };

    $(".list-qa .content .content__left").each(function (e) {
        let elHeight = $(this).height();
        let divMaxHeight = getMaxHeight($(this));

        let paddingRight = $(this).css("padding-right") || 0;
        if (elHeight > divMaxHeight) {
            $(this).css("max-height", divMaxHeight + "px");
            let link = $(this).find("a.more");
            link.css({
                position: "absolute",
                right: 0,
                bottom: 0,
                backgroundColor: "#fff",
                "z-index": "2",
                "padding-left": "15px",
            });
        } else {
        }
    });

    var resizeTimer;

    $(window).on("resize", function () {
        $(".list-qa .content .content__left").each(function (e) {
            let elHeight = $(this).height();
            let divMaxHeight = getMaxHeight($(this));
            let paddingRight = $(this).css("padding-right") || 0;
            if (elHeight > divMaxHeight) {
                $(this).css("max-height", divMaxHeight + "px");
                let link = $(this).find("a.more");
                link.css({
                    position: "absolute",
                    right: 0,
                    bottom: 0,
                    backgroundColor: "#fff",
                    "z-index": "50",
                    "padding-left": "15px",
                });
            } else {
            }
        });
    });

    function getMaxHeight(element) {
        let el = element[0];
        const style = window.getComputedStyle(el);
        return 1.5 * parseInt(style.fontSize.replace("px", "")) * 5;
    }

    $(document).on("click", ".delete", function () {
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        var url = $(this).data("route");
        Swal.fire({
            title: "本当に削除しますか ?",
            showCancelButton: true,
            cancelButtonColor: "#F0F0F0",
            confirmButtonColor: "#d33",
            showCloseButton: true,
            cancelTextColor: "black",
            confirmButtonText: "はい",
            cancelButtonText: "いいえ",
            cancelText: "20px",
            width: "700px",

        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: "DELETE"
                }).done(function (res) {
                    Swal.fire({
                        title: "削除されました",
                        timer:3000
                    }).then(() => {
                        location.reload()
                    })
                });
            }
        });
    });
})(jQuery);
