const { forEach } = require("lodash");

(function ($) {
    "use strict";

    let lastTab = localStorage.getItem("lastTab");
    $(".tab-content").removeClass("hidden");
    if (lastTab) {
        const firstTab = new bootstrap.Tab($('[data-bs-target="' + lastTab + '"]'));
        firstTab.show()
    }

    $('button[data-bs-toggle="tab"]').each(function (i) {
        $(this).on("click", function (e) {
            localStorage.setItem("lastTab", $(this).data("bs-target"));
        });
    });

    const xValues = [
        "Mar 30, 2023 9:38 am",
        "Mar 30, 2023 9:19 am",
        "Mar 30, 2023 9:19 am",
    ];
    const yValues = [0, 50, 100];

    var charts = $(".myChart");
    charts.each(function () {
        let ctx = this.getContext("2d");
        var myChart = new Chart(this, {
            type: "line",
            data: {
                labels: xValues,
                datasets: [
                    {
                        data: yValues,
                        fill: true,
                        lineTension: 1,
                        backgroundColor: "#FFE8E6",
                        borderColor: "#D72619",
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        // defining min and max so hiding the dataset does not change scale range
                        min: 0,
                        max: 100,
                        ticks: {
                            stepSize: 20,
                        },
                    },
                },
            },
        });
    });

    function submitForm() {
        let tab = $("#number-accesses");
        let tabActive = $("#myTab").find("button.active[role=tab]");
        if (tabActive) {
            tab = tabActive;
        }
        $('input[name="tab"]').val(tabActive.attr("id"));
        $("#analysisForm").submit();
    }

    $("button.btn-filter").on("click", function () {
        submitForm();
    });

    $('select[name="order"]').change(function () {
        submitForm();
    });
})(jQuery);
