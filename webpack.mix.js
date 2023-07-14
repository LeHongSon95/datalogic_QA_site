const mix = require("laravel-mix").sourceMaps(true, "source-map");

mix.webpackConfig((webpack) => {
    return {
        plugins: [
            new webpack.ProvidePlugin({
                $: "jquery",
                jQuery: "jquery",
                "window.jQuery": "jquery",
            }),
        ],
        watchOptions: {
            ignored: ["vendor", "public/assets"],
        },
    };
});
mix.disableNotifications();
mix.browserSync({
    proxy: "http://127.0.0.1:8000",
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 */

// libs
mix.js("resources/assets/js/libs/bootstrap.js", "public/assets/js/libs")
    .copyDirectory(
        "node_modules/chart.js/dist/chart.umd.js",
        "public/assets/js/libs/chart/dist"
    )
    .sass("resources/assets/scss/libs/bootstrap.scss", "public/assets/css/libs")
    .sass("resources/assets/scss/libs/select2.scss", "public/assets/css/libs");

// global
mix.js("resources/assets/js/pages/general.js", "public/assets/js/pages").sass(
    "resources/assets/scss/style.scss",
    "public/assets/css/"
);

mix.copyDirectory('vendor/tinymce/tinymce', 'public/assets/js/libs/tinymce');

mix.sass("resources/assets/scss/custom/tinymce.scss", "public/assets/css/custom");

// template home
mix.js("resources/assets/js/pages/qa-top.js", "public/assets/js/pages").sass(
    "resources/assets/scss/pages/qa-top.scss",
    "public/assets/css/pages"
);

// template detail
mix.sass("resources/assets/scss/pages/detail.scss", "public/assets/css/pages")
    .js("resources/assets/js/pages/qa-show.js", "public/assets/js/pages");

// template favorite
mix.sass("resources/assets/scss/pages/favorite.scss", "public/assets/css/pages")
    .js('resources/assets/js/pages/favorite.js', 'public/assets/js/pages');

// template dashboard
mix.js("resources/assets/js/pages/dashboard.js", "public/assets/js/pages").sass(
    "resources/assets/scss/pages/dashboard.scss",
    "public/assets/css/pages"
);

// template dashboard
mix.js(
    "resources/assets/js/pages/questionnaire.js",
    "public/assets/js/pages"
).sass(
    "resources/assets/scss/pages/questionnaire.scss",
    "public/assets/css/pages"
);

// style change font size
mix.sass(
    "resources/assets/scss/change-font-size/big.scss",
    "public/assets/css/change-font-size"
).sass(
    "resources/assets/scss/change-font-size/small.scss",
    "public/assets/css/change-font-size"
);

// input search components
mix.js(
    "resources/assets/js/components/form-input-search.js",
    "public/assets/js/components"
);

// template admin setting
mix.js('resources/assets/js/pages/setting.js', 'public/assets/js/pages')
    .sass('resources/assets/scss/pages/setting.scss', 'public/assets/css/pages')   
