module.exports = {
  devtool: 'false',
  entry:{
    vendor:["vue","antd","other"]
  },
  plugins:[
    new webpack.optimize.CommonsChunkPlugin({
      name: 'default',
      // filename: "vendor.js"
      // (给 chunk 一个不同的名字)

      minChunks: Infinity,
      // (随着 entry chunk 越来越多，
      // 这个配置保证没其它的模块会打包进 vendor chunk)
    }),
    new webpack.optimize.CommonsChunkPlugin({
      name: 'Courses',
      // filename: "vendor.js"
      // (给 chunk 一个不同的名字)

      minChunks: Infinity,
      // (随着 entry chunk 越来越多，
      // 这个配置保证没其它的模块会打包进 vendor chunk)
    }),
    new webpack.optimize.CommonsChunkPlugin({
      name: 'CoursePublish',
      // filename: "vendor.js"
      // (给 chunk 一个不同的名字)

      minChunks: Infinity,
      // (随着 entry chunk 越来越多，
      // 这个配置保证没其它的模块会打包进 vendor chunk)
    }),
    new webpack.optimize.CommonsChunkPlugin({
      name: 'Course',
      // filename: "vendor.js"
      // (给 chunk 一个不同的名字)

      minChunks: Infinity,
      // (随着 entry chunk 越来越多，
      // 这个配置保证没其它的模块会打包进 vendor chunk)
    }),
    new webpack.optimize.CommonsChunkPlugin({
      name: 'CourseCatalog',
      // filename: "vendor.js"
      // (给 chunk 一个不同的名字)

      minChunks: Infinity,
      // (随着 entry chunk 越来越多，
      // 这个配置保证没其它的模块会打包进 vendor chunk)
    }),
    new webpack.optimize.CommonsChunkPlugin({
      name: 'CoursecatalogUpload',
      // filename: "vendor.js"
      // (给 chunk 一个不同的名字)

      minChunks: Infinity,
      // (随着 entry chunk 越来越多，
      // 这个配置保证没其它的模块会打包进 vendor chunk)
    })
  ]
}