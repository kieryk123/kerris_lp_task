const path = require('path');

// include the js minification plugin
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');

// include the css extraction and minification plugins
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');

// include the css autoprefixer
const Autoprefixer = require('autoprefixer');

// include Browsersync plugin
const BrowserSync = require('browser-sync-webpack-plugin');

const autoprefixerOptions = {
  browsers: [
      'defaults',
      'last 4 versions',
      'last 6 iOS versions',
      'last 6 Android versions',
      'last 6 Safari versions',
      'last 2 ie versions',
      'ie >= 10'
  ]
}

module.exports = {
  entry: ['./js/main.js', './sass/main.scss'],
  output: {
    filename: './dist/js/main.min.js',
    path: path.resolve(__dirname)
  },
  devtool: 'source-map',
  module: {
    rules: [
      // perform js babelization on all .js files
      {
        test: /\.js$/,
        exclude: [
          /node_modules/,
          /images/
        ],
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      },
      // compile all .scss files to plain old css
      {
        test: /\.(sass|scss)$/,
        exclude: [
          /node_modules/,
          /images/
        ],
        use: [
          {
            loader: MiniCssExtractPlugin.loader
          },
          {
            loader: 'css-loader',
            options: {
              sourceMap: true
            }
          },
          {
            loader: 'postcss-loader',
            options: {
              plugins: () => [
                Autoprefixer(autoprefixerOptions)
              ]
            }
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: true
            }
          }
        ]
      },
      {
        test: /\.(png|svg|jpg|gif)$/,
        use: {
          loader: 'file-loader',
          options: {
            name: '../../images/[name].[ext]'
          }
        }
      }
    ]
  },
  plugins: [
    // extract css into dedicated file
    new MiniCssExtractPlugin({
      filename: './dist/css/main.min.css'
    }),
    // run local server
    new BrowserSync({
      // browse to http://localhost:3000/ during development,
      // curent directory is being served
      host: 'localhost',
      port: 3000,
      server: { baseDir: ['./'] },
      // add files to watch for changes
      files: './*.html',
      // don't show notifications in the browser
      notify: false
    })
  ],
  optimization: {
    minimizer: [
      // enable the js minification plugin
      new UglifyJSPlugin({
        cache: true,
        parallel: true
      }),
      // enable the css minification plugin
      new OptimizeCSSAssetsPlugin({})
    ]
  }
};
