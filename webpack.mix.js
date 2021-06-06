const path = require('path');
const mix = require('laravel-mix');

require('laravel-mix-alias')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */


mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.join(__dirname, '/resources/js'),
            'Admin': path.join(__dirname, '/resources/js/admin'),
            'Campaign': path.join(__dirname, '/resources/js/campaign')
        }
    },
    module: {
        rules: [
            {
                test: /\.svg$/,
                use: [
                    {
                        loader: "vue-svg-loader",
                        options: { /* ... */ }
                    }
                ]
            }
        ]
    }
});

mix
    .js('resources/js/admin/app.js', 'public/js/admin.js').vue()
    .js('resources/js/campaign/app.js', 'public/js').vue()
    .sass('resources/sass/app.scss', 'public/css');

mix.copyDirectory('node_modules/tinymce/skins', 'public/skins');
