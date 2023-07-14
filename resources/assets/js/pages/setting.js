require("select2");
require("jquery-ui-dist/jquery-ui");
(function ($) {
    "use strict";

    $(document).ready(() => {
        $(document).on(
            "click",
            ".draggable-item, .recomanded-item",
            function (param) {
                let id = $(this).data("id");
                let inputName = $(this).data("name");
                let itemClass = $(this).data("class");
                let data = $("input[name=" + inputName + "]").val() ? JSON.parse($("input[name=" + inputName + "]").val() ) : [];
                
                if ($(this).hasClass("active")) {
                    if (data.includes(id)) {
                        data = data.filter(item => item != id)
                    } 
                    $("input[name=" + inputName + "]").val(JSON.stringify(data));
                    $(this).removeClass("active");
                } else {
                    if (!data.includes(id)) {
                        data.push(id)
                    } 
                    $("input[name=" + inputName + "]").val(JSON.stringify(data));
                    $(this).addClass("active");

                }
            }
        );

        $("#QaRecomanded").select2();

        $(".sort-keywords")
            .sortable({
                connectWith: ".connected-sortable",
                stack: ".connected-sortable ul",
                update: function (e, ui) {
                    let listTag = $(".sort-keywords .draggable-item");
                    let url = $(".sort-keywords").data("url");
                    let data = [];
                    let i = 1;
                    listTag.each(function () {
                        let id = $(this).data("id");
                        let option = {};
                        option['id'] = id;
                        option['title'] = $(this).data("title");
                        option['is_featured'] = $(this).data("featured");
                        option['order'] = i;
                        data.push(option);
                        i++;
                    });
                    updateSort(data, url);
                },
            })
            .disableSelection();

        var updateSort = function (data, url) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: url,
                type: "POST",
                data: {data: data},
                success: function (res, sec) {
                },
                error: function (res, sec) {
                },
            });
        };
    });
})(jQuery);
