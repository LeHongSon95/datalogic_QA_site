require('bootstrap');

(function ($) {
    "use strict";
    function enableComment() {
        let status = document.querySelector('input[name="status"]:checked').value;
        if (status == 1) {
            $("#comment").attr("disabled", "true");
            $("#comment").val('')
        } else {
            $("#comment").removeAttr("disabled");
        }
    }

    function youtube_parser(url){
        var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
        var match = url.match(regExp);
        return (match && match[7].length == 11) ? match[7] : false;
    }

    $(document).ready(() => {
        enableComment();
        $('input[name="status"]').on("change", function (param) {
            enableComment();
        });

        $('.content').find('a').each(function() {
            let href = $(this).attr('href');
            if (youtube_parser(href)) {
                let src = 'https://www.youtube.com/embed/' + youtube_parser(href);
                $(this).closest('p').append('<iframe width="600" height="345" src="'+src+'"></iframe>');
                $(this).remove();
            }
        })
    });

    $('.btn-close-alert').on('click', function(e) {
        $(this).parents('.alert').addClass('notransition');
    })

    $('.qa-post img').on('click', function(e) {
        let src = $(this).attr('src');
        $('#modal .modal-body').html('<img src="'+src+'" alt="">');
        $('#modal').modal('show');
    })

    $('.btn-close').on('click', function(e) {
        $('#modal').modal('hide');
    })
})(jQuery);
