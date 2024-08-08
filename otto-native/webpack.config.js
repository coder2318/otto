const webpack = require('@nativescript/webpack')
const Dotenv = require('dotenv-webpack')
const path = require('path')

module.exports = (env) => {
  webpack.init(env)

  // Learn how to customize:
  // https://docs.nativescript.org/webpack

  webpack.chainWebpack((config) => {
    config.plugin('dotenv').use(Dotenv, [
      {
        path: path.resolve(__dirname, `.env.${env.production ? 'production' : 'development'}`),
        safe: true,
        systemvars: true,
      },
    ])
  })

  return webpack.resolveConfig()
}
