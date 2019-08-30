const path = require('path')
const webpack = require('webpack')

module.exports = {
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
      '#': path.resolve(__dirname, 'resources/sass')
    }
  },
  plugins: [

  ],
  module: {
    rules: [
      {
        test  : /\.vue$/,
        loader: 'vue-loader'
      },
      {
        test   : /\.js$/,
        include: [path.resolve(__dirname, './node_modules/vue2-google-maps')],
        loader : 'babel-loader',
        exclude: /node_modules/,
        options: {
          plugins: [require('babel-plugin-syntax-dynamic-import')]
        }
      },
      {
        test   : /\.(png|jpg|gif|svg)$/,
        loader : 'file-loader',
        options: {
          name: '[name].[ext]?[hash]'
        }
      }
    ]
  }
}

// test specific setups
if (process.env.NODE_ENV === 'test') {
  module.exports.externals = [require('webpack-node-externals')()]
  module.exports.devtool   = 'inline-cheap-module-source-map'
}