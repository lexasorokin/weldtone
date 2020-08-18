/**
 * This is a basic Webpack 4 setup to output a bundle.js
 * and a separate main.css file
 * 
 * This setup requires installing the following devDependencies:
 * - @babel/core
 * - @babel/preset-env
 * - babel-loader
 * - css-loader
 * - mini-css-extract-plugin
 * - node-sass
 * - sass-loader
 * - webpack
 * - webpack-cli
 * 
 * This setup is expecting the following folder structure:
 * - dist/
 * - src/
 *   - js/
 *     - index.js
 *       ..other .js files or folders
 *   - scss/
 *     - main.scss
 *       ...other .scss files or folders
 * 
 * Also make sure your index.js pulls in the main.scss file
 * by including the following code at the top of index.js:
 * - import '../scss/main.scss';
 */

const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  mode: process.env.NODE_ENV === 'production' ? 'production' : 'development',
  entry: './src/js/index.js',
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: 'bundle.js',
    publicPath: '/dist',
  },
  devtool: 'source-map',
  module: {
    rules: [
      {
        test: /\.m?js$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: 'babel-loader',
          options: { presets: ['@babel/preset-env'] },
        },
      },
      {
        test: /\.s?css$/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader',
            options: {
              sourceMap: true,
            }
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: true,
            }
          }
        ]
      }
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "[name].css",
      chunkFilename: "[id].css"
    })
  ],
  watchOptions: {
    aggregateTimeout: 500,
  }
};