require("select2");
require('select2/dist/js/i18n/ja');

(function ($) {
    "use strict";

    $(document).ready(() => {
        // define const
        const MAX_QUESTION_ITEM = 5;
        $(document).on("click", ".btn-add-question", function (param) {
            let id = $(".question-item:visible").length;
            $("#question"+id).closest('.question-item').removeClass('d-none');
            if ($(".question-item:visible").length == MAX_QUESTION_ITEM) {
                $(this).attr("disabled", true);
            }
        });
        $(document).on("click", ".remove_question", function (param) {
            $('.btn-add-question').attr("disabled", false);
            if ($(this).parents(".question-item").find('textarea[name="questions[]"]').attr('id') == 'question0') {
                return;
            }
            let id = $(this).parents(".question-item").find('textarea[name="questions[]"]').attr('id');
            tinymce.get(id).setContent('');
            $(this).parents(".question-item").addClass('d-none');
        });

        $(document).on("click", ".question-item .clear", function () {
            $(this).closest('.question-item').find('input').val('');
            $(this).addClass('d-none');
        });

        $(document).on("keyup", "input[name='questions[]']", function () {
            if ($(this).val()) {
                $(this).closest('.question-item').find('.clear').removeClass('d-none');
            } else {
                $(this).closest('.question-item').find('.clear').addClass('d-none');
            }
        });

        // select hashtag
        $("#hashtag").select2({tags: true});
        $("#categories").select2();
        $("#relatedQuestion").select2();

        $("#hashtag").on("change", function (e) {
            loadRelatedQuestion();
        });

        $("#hashtag").on("select2:unselecting", function (e) {
            let id = e.params.args.data.id;
            $('.li-question').each(function() {
                let tag = $(this).attr('data-tag');
                if (tag.search(id) >= 0) {
                    $(this).find('.delete-related-question').trigger("click");
                }
            });
        });

        $(".btn-add-related-question").on("click", function (e) {
            if ($("#relatedQuestion option").length == 0) return;
            let data = $("#relatedQuestion").select2("data")[0];
            let tag = $('#relatedQuestion option:selected').attr('data-tag');

            let relatedQuestion = $("form.form-add-qa").find(
                'input[name="related_question_input"]'
            );
            if (relatedQuestion.length == 0) {
                $("form.form-add-qa").append(
                    $(
                        '<input name="related_question_input" type="hidden" value="">'
                    )
                );
            }
            let relatedQuestionValue = relatedQuestion.val()
                ? JSON.parse(relatedQuestion.val())
                : {};

            if ($("input[name='data_tag']").length == 0) {
                $("form.form-add-qa").append(
                    $(
                        '<input name="data_tag" type="hidden" value="">'
                    )
                );
            }
            let dataTag = $("input[name='data_tag']").val() ? JSON.parse($("input[name='data_tag']").val()) : {};
            
            if (Object.keys(relatedQuestionValue).length == 3) {
                $(this).attr("disabled", true);
                return;
            }
            if (!relatedQuestionValue[data.id]) {
                relatedQuestionValue[data.id] = data.text;
                dataTag[data.id] = tag;

                $("input[name='data_tag']").val(JSON.stringify(dataTag));

                $('input[name="related_question_input"]').val(
                    JSON.stringify(relatedQuestionValue)
                );
                $("#relatedQuestion").data(
                    "disabled",
                    JSON.stringify(relatedQuestionValue)
                );

                $("#relatedQuestion")
                    .find($("option[value=" + data.id + "]"))
                    .attr("disabled", true);
                $(".control-related-question").append(
                    $(
                        "<li class='li-question' data-id='" +data.id+ "' data-tag='" +tag+ "'>" + data.text +
                            "<span class='material-icons delete-related-question'>remove_circle_outline</span>  </li>"
                    )
                );
            }
        });
        
        $("#categories").on("select2:opening select2:closing", function (e) {
            let $searchfield = $(this).parent().find('.select2-search__field');
            $searchfield.prop('disabled', true);
        });

        $(document).on("keyup", ".control-hashtag .select2-search__field", function (event) {
            let val = $(this).val();
            val = val.replace(/\s/g, '');
            $(this).val(val);
        });

        let relatedQuestion = $("#relatedQuestion").data("disabled");
        $(".control-related-question").remove("li");
        if (relatedQuestion) {
            let value = relatedQuestion;
            let tag = $('#relatedQuestion').data('tag');
            if (Object.keys(value).length > 0) {
                Object.keys(value).forEach((index) => {
                    $(".control-related-question").append(
                        $(
                            "<li class='li-question' data-tag ='" +tag[index]+ "' data-id=" +index+ ">" +value[index]+
                                "<span class='material-icons delete-related-question'>remove_circle_outline</span>  </li>"
                        )
                    );
                });
                $("form.form-add-qa").append(
                    $(
                        '<input name="related_question_input" type="hidden" value=' +
                            JSON.stringify(value) +
                            ">"
                    )
                );
                $("form.form-add-qa").append(
                    $(
                        '<input name="data_tag" type="hidden" value=' +
                            JSON.stringify(tag) +
                            ">"
                    )
                );
            }
        }

        $(document).on("click", ".delete-related-question", function (param) {
            $('.btn-add-related-question').attr("disabled", false);
            let parent = $(this).parents("li");
            let id = parent.data("id");
            parent.remove();
            let relatedQuestionData = $(
                'input[name="related_question_input'
            ).val();

            let newData = {};
            Object.entries(JSON.parse(relatedQuestionData)).map(
                ([key, value]) => {
                    if (key != id) {
                        newData[key] = value;
                    }
                    return true;
                }
            );

            $("#relatedQuestion").data("disabled", JSON.stringify(newData));

            $("#relatedQuestion")
                .find($("option[value=" + id + "]"))
                .attr("disabled", false);
            $('input[name="related_question_input"]').val(
                JSON.stringify(newData)
            );
        });

        loadRelatedQuestion();

        function loadRelatedQuestion() {
            let hashTag = $("#hashtag").select2("val");
            if (hashTag.length > 0) {
                let token =
                    document.head.querySelector("[name=csrf-token]").content;
                let url = $("#relatedQuestion").data("url");
                let param = {
                    hashTag: hashTag,
                    _token: token,
                };
                let data = getQaByTag(url, param);
                if (data.length > 0) {
                    $("#relatedQuestion").empty();
                    let dataDisabled = $(
                        'input[name="related_question_input"]'
                    ).val()
                        ? Object.keys(
                            JSON.parse(
                                $('input[name="related_question_input"]').val()
                            )
                        )
                        : [];
                    data.forEach((element) => {
                        let disabled = dataDisabled.includes(String(element.id))
                            ? "disabled='true'"
                            : "";
                            
                        let strTag = '';
                        element.tags.forEach(tag => strTag += tag.id + ',');
                        strTag = strTag.slice(0, -1);

                        $("#relatedQuestion").append(
                            $(
                                "<option data-tag ='" + strTag + "'  value='" +
                                    element.id +
                                    "' " +
                                    disabled +
                                    ">" +
                                    element.title +
                                    "</option>"
                            )
                        );
                    });
                    $("#relatedQuestion").select2();
                } else {
                    $("#relatedQuestion").empty();
                }
            } else {
                $("#relatedQuestion").empty();
            }
        }

        function getQaByTag(url, param) {
            var result = [];
            $.ajax({
                url: url,
                dataType: "json",
                type: "POST",
                data: param,
                async: false,
                success: function (response) {
                    result = response;
                },
            });
            return result;
        }

        $("#btnClear").on("click", function (e) {
            let urlParams = new URLSearchParams(window.location.search);
            let clear = urlParams.get('clear');

            if (clear) {
                window.location.href;
            } else {
                window.location.href += "?clear=1";
            }
        });

        $("#btnSubmit").on("click", function (e) {
            $('textarea[name="questions[]"').each(function( index ) {
                if ($(this).closest('.question-item').hasClass('d-none')) {
                    $(this).attr("disabled", true);
                }
            })
            $("#btnSubmit").closest('form').submit();
            $(this).attr("disabled", true);
        })
    });
})(jQuery);
