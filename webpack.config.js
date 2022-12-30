const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const { VueLoaderPlugin } = require('vue-loader')
const Webpack = require('webpack')

module.exports = [
    {
        name: 'scripts',
        mode: process.env.NODE_ENV,
        entry: {
            admin: './resources/js/admin/app.js',
            campaign: './resources/js/campaign/app.js'
        },
        resolve: {
            alias: {
                vue: 'vue/dist/vue.runtime.esm-bundler.js',
                '@admin': path.resolve(__dirname, 'resources/js/admin'),
                '@campaign': path.resolve(__dirname, 'resources/js/campaign'),
                '@components': path.resolve(__dirname, 'resources/js/components'),
                '@stores': path.resolve(__dirname, 'resources/js/stores')
            },
            extensions: ['*', '.js', '.vue', '.json', 'png', 'gif', 'jpeg', 'jpg']
        },
        plugins: [
            new VueLoaderPlugin(),
            new Webpack.DefinePlugin({
                __VUE_OPTIONS_API__: false,
                __VUE_PROD_DEVTOOLS__: false
            })
        ],
        module: {
            rules: [
                {
                    test: /\.vue$/,
                    loader: 'vue-loader'
                },
                {
                    test: /\.exec\.js$/,
                    use: 'script-loader'
                }
            ]
        },
        output: {
            filename: 'js/[name].js',
            path: path.join(__dirname, 'public')
        }
    },
    {
        name: 'scss',
        mode: process.env.NODE_ENV,
        entry: {
            app: path.join(__dirname, 'resources/sass/app.scss')
        },
        plugins: [
            new MiniCssExtractPlugin({ filename: '[name].css' })
        ],
        module: {
            rules: [
                {
                    // Fetch sass files
                    test: /\.(c|sc|sa)ss$/,
                    /*
                     * Find scss and convert to CSS (sass-loader)
                     * Add PostCSS optimisations (postcss-loader)
                     * Convert (Post)CSS output to CommonJS (css-loader)
                     * Extract CSS from CommonJS output and write to separate file (MiniCssExtractPlugin)
                     */
                    use: [
                        MiniCssExtractPlugin.loader,
                        {
                            loader: 'css-loader',
                            options: {
                                importLoaders: 1,
                                url: false
                            }
                        },
                        'postcss-loader', // post process the compiled CSS
                        'sass-loader'
                    ]
                }
            ]
        },
        output: {
            filename: '[chunkHash].js',
            path: path.join(__dirname, 'public/css')
        }
    }

]
// module.exports.parallelism = 2;