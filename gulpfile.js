'use strict';

var gulp        = require('gulp');
var del         = require("del");
var browserSync = require('browser-sync').create();
var sass        = require('gulp-sass');


//********************************************************
// main function
//********************************************************
gulp.task('default', ['delHtml','delSass','copy','sass'], function() {
  browserSync.init({
    server: {
      baseDir: "html"
    }
  });

  gulp.watch(['_resource/**/*.html'], ['delHtml', 'copy']);
  gulp.watch(['_resource/sass/*.scss'], ['delSass', 'sass']);
});

//********************************************************
// sub function
//********************************************************
gulp.task("delHtml", function() {
  del(["html/*.html"]);
});
gulp.task("delSass", function() {
  del(["html/css/*.css"]);
});

gulp.task('copy', function() {
  return gulp.src([
    '_resource/**/*.html'
  ])
  .pipe(gulp.dest('html/'))
  .pipe(browserSync.stream());
});

gulp.task('sass',function() {
  return gulp.src([
    '_resource/sass/**/*.scss'
  ])
  .pipe(sass({outputStyle:'expanded'}))
  .pipe(gulp.dest('html/css/'))
  .pipe(browserSync.stream());
})


