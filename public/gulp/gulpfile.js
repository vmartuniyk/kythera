// Include gulp
var gulp = require('gulp'); 
var gutil = require('gulp-util'); 

// Include Our Plugins
//http://www.sitepoint.com/introduction-gulp-js/
//http://blog.nodejitsu.com/npmawesome-9-gulp-plugins/
//http://christoph-rumpel.com/2014/02/how-to-laravel-series-lets-talk-gulp/
//npm install gulp gulp-util gulp-jshint gulp-concat gulp-uglify gulp-rename gulp-minify-html gulp-autoprefixer gulp-minify-css gulp-changed gulp-imagemin --save-dev 
var jshint = require('gulp-jshint');
//var sass   = require('gulp-sass');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var filesize = require('gulp-filesize');

var autoprefix = require('gulp-autoprefixer');
var minifyCSS = require('gulp-minify-css');

var changed = require('gulp-changed');
var imagemin = require('gulp-imagemin');

var minifyHTML = require('gulp-minify-html');


// Lint Task
gulp.task('lint', function() {
    return gulp.src('./src/scripts/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'))
        .on('error', gutil.log);
});



// Concatenate & Minify JS
gulp.task('js', function() {
    return gulp.src('./src/scripts/*.js')
        .pipe(concat('all.js'))
        .pipe(uglify())
        .pipe(rename('all.min.js'))
        .pipe(gulp.dest('./build/scripts/'))
        .on('error', gutil.log);
});

// Concatenate & Minify CSS
gulp.task('css', function() {
    return gulp.src('./src/css/*.css')
        .pipe(concat('all.css'))
        .pipe(autoprefix('last 2 versions'))
        .pipe(minifyCSS())
        .pipe(rename('all.min.css'))
        .pipe(gulp.dest('./build/css/'))
        .on('error', gutil.log);
});

/*
// minify new or changed HTML pages
gulp.task('htmlpage', function() {
  var htmlSrc = './src/*.html',
      htmlDst = './build';
 
  gulp.src(htmlSrc)
    .pipe(changed(htmlDst))
    .pipe(minifyHTML())
    .pipe(gulp.dest(htmlDst));
});
*/

/*
// minify new images
gulp.task('imagemin', function() {
  var imgSrc = './src/images/** / *',
      imgDst = './build/images';
 
  gulp.src(imgSrc)
    .pipe(changed(imgDst))
    .pipe(imagemin())
    .pipe(gulp.dest(imgDst));
});
*/



/*
// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch('js/*.js', ['lint', 'scripts']);
    gulp.watch('scss/*.scss', ['sass']);
});
*/

// Default Task
//gulp.task('default', ['lint', 'sass', 'scripts', 'watch']);g
gulp.task('default', ['lint', 'scripts']);