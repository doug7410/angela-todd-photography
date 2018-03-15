const nodeExternals = require('webpack-node-externals')
const laravelMixWebpack = require('laravel-mix/setup/webpack.config')
const merge = require('webpack-merge')

module.exports = merge([laravelMixWebpack, {
  externals: [nodeExternals()],
  output: {
    devtoolModuleFilenameTemplate: '[absolute-resource-path]',
    devtoolFallbackModuleFilenameTemplate: '[absolute-resource-path]?[hash]'
  },
  devtool: '#inline-cheap-module-source-map'
}
])
