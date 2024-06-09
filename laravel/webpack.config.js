const mix = require('laravel-mix')

var ASSET_URL = "/";
if (process.env.NODE_ENV === 'production' && process.env.ASSET_URL) {
  ASSET_URL = process.env.ASSET_URL + '/'
}
const path = require('path')
const webpack = require('webpack')

module.exports = {
  plugins: [
    // new BundleAnalyzerPlugin(),
    new webpack.DefinePlugin({
      "process.env.ASSET_PATH": JSON.stringify(ASSET_URL)
    })
  ],
  resolve: {
    extensions: ['.js', '.json', '.vue'],
    alias: {
      '~': path.join(__dirname, './resources/js')
    }
  },
  output: {
    chunkFilename: 'dist/js/[chunkhash].js',
    path: mix.config.hmr ? '/' : path.resolve(__dirname, './public/build'),
    publicPath: ASSET_URL
  },
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.(js|vue)$/,
        loader: 'eslint-loader',
        exclude: /(node_modules)/,
        options: {
          emitError: true,
          emitWarning: true,
          failOnError: true,
          failOnWarning: true
        }
      }
    ]
  }
}
