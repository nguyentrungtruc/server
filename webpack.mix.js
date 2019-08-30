const mix = require('laravel-mix');
const fs                  = require('fs')
const {resolve, plugins } = require('./webpack.config.js')

mix.webpackConfig({ resolve, plugins })
mix.setResourceRoot('./public/')
if (mix.config.hmr) {
    // There's a bug with Mix/copy plugin which prevents HMR from working:
    // https://github.com/JeffreyWay/laravel-mix/issues/150
    console.log('In HMR mode. If assets are missing, Ctr+C and run `yarn dev` first.')
  
    // Somehow public/hot isn't being removed by Mix. We'll handle it ourselves.
    process.on('SIGINT', () => {
        try {
            fs.unlinkSync(mix.config.publicPath + '/hot')
        } catch (e) {
        }
        process.exit()
    })
  } else {
      mix.copy('resources/assets/img', 'public/img', false)
      // .copy('node_modules/font-awesome/fonts', 'public/fonts', false)
  }
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

mix.js('resources/js/app.js', 'public/js').sass('resources/sass/app.scss', 'public/css')
 
if (mix.config.inProduction) {
 	mix.version()
 	mix.disableNotifications()
}
