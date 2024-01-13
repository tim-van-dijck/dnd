const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')

module.exports = [
  {
    name: 'scripts',
    mode: process.env.NODE_ENV,
    stats: {
      errorDetails: true
    },
    entry: {
      admin: './resources/js/apps/admin/index.tsx',
      campaign: './resources/js/apps/campaign/index.tsx'
    },
    resolve: {
      alias: {
        '@dnd/admin': path.resolve(__dirname, 'resources/js/apps/admin'),
        '@dnd/campaign': path.resolve(__dirname, 'resources/js/apps/campaign'),
        '@dnd/components': path.resolve(__dirname, 'resources/js/components'),
        '@dnd/libs': path.resolve(__dirname, 'resources/js/libs'),
        '@dnd/stores': path.resolve(__dirname, 'resources/js/stores'),
        '@dnd/services': path.resolve(__dirname, 'resources/js/services')
      },
      extensions: ['.ts', '.tsx', '.js', '.d.ts', '.json', 'png', 'gif', 'jpeg', 'jpg']
    },
    module: {
      rules: [
        {
          test: /\.(js|tsx?)$/,
          exclude: /node_modules/,
          loader: 'babel-loader'
        },
        {
          test: /\.exec\.js$/,
          use: 'script-loader'
        }
      ]
    },
    output: {
      filename: '[name].js',
      path: path.join(__dirname, 'public/js')
    }
  },
  {
    name: 'scss',
    mode: process.env.NODE_ENV,
    entry: {
      app: path.join(__dirname, 'resources/sass/app.scss')
    },
    plugins: [
      new MiniCssExtractPlugin({
        filename: '[name].css',
        chunkFilename: '[id].[contenthash].css'
      })
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
      path: path.join(__dirname, 'public/css')
    }
  }
]
// module.exports.parallelism = 2;