const path = require('path')
const fs = require('fs-extra')
const mix = require('laravel-mix')
const tailwindcss = require('tailwindcss')
const config = require('./webpack.config')

require('laravel-mix-versionhash')
require('laravel-mix-purgecss')
// const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer')

mix
  .js('resources/js/app.js', 'public/dist/js')
  .sass('resources/sass/app.scss', 'public/dist/css')
  .options({
    processCssUrls: false,
    postCss: [ tailwindcss('./tailwind.js') ]
  })
  .disableNotifications()

if (mix.inProduction()) {
  mix
    .purgeCss() // Remove CSS that we are not using with `laravel-mix-purgecss`
    // .extract() // Disabled until resolved: https://github.com/JeffreyWay/laravel-mix/issues/1889
    // .version() // Use `laravel-mix-versionhash` for the generating correct Laravel Mix manifest file.
    // .versionHash()
} else {
  mix.sourceMaps()
    .versionHash()
}

// webpack.config.js
mix.webpackConfig(config)

mix.then(() => {
  if (!mix.config.hmr) {
    process.nextTick(() => publishAseets())
  }
})

function publishAseets () {
  const publicDir = path.resolve(__dirname, './public')

  if (mix.inProduction()) {
    fs.removeSync(path.join(publicDir, 'dist'))
  }

  fs.copySync(path.join(publicDir, 'build', 'dist'), path.join(publicDir, 'dist'))
  fs.removeSync(path.join(publicDir, 'build'))
}
