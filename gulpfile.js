/*
 * Gulp file
 * Paul Collett paulcollett.com
*/

// Gulp & Gulp Plugins
var gulp         = require('gulp');
var plumber      = require('gulp-plumber');
var sass         = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps   = require('gulp-sourcemaps');
var uglify       = require('gulp-uglify');
var notify       = require('gulp-notify');
var rename       = require("gulp-rename");

// Original Asset Paths
var src = {
  css: 'assets_src/**/*.{scss,sass}',
  js: 'assets_src/*.js',
};

// Destination Asset Paths
var dest = {
  css: 'assets',
  js: 'assets',
};

// Main Tasks
gulp.task('default', function(){
  gulp.start('build');
});

gulp.task('watch',['default'],function(){
  gulp.watch(src.css, {cwd:'./'}, ['compile-sass-to-css-min-and-move', 'compile-sass-to-css']);
  gulp.watch(src.js, {cwd:'./'}, ['compile-js-to-min', 'compile-js']);
});

gulp.task('build',[
  'compile-sass-to-css-min-and-move',
  'compile-sass-to-css',
  'compile-js-to-min',
  'compile-js'
]);

//move-site-css-to-style-css
gulp.task('compile-sass-to-css-min-and-move', ['compile-sass-to-css-min'], function() {
  return gulp.src( dest.css + '/style.css' )
    .pipe(gulp.dest( './' ));
});

gulp.task('compile-sass-to-css', function(){
  return gulp.src( src.css )
    .pipe(plumber())
    .pipe(sass({
      outputStyle: 'expanded',
      precision: 4,
    }))
    .pipe(autoprefixer({
      browsers: ['ie >= 8', '> 1%']
    }))
    .pipe(rename(function (path) {
      path.basename += ".source";
    }))
    .pipe(gulp.dest( dest.css ));
});

gulp.task('compile-sass-to-css-min', function(){
  return gulp.src( src.css )
    .pipe(plumber({
        errorHandler: css_error_os_alert
    }))
    //.pipe(sourcemaps.init())
    .pipe(sass({
      outputStyle: 'compressed',
      precision: 4,
    }))
    .pipe(autoprefixer({
      browsers: ['ie >= 8', '> 1%']
    }))
    //.pipe(sourcemaps.write( './'))
    .pipe(gulp.dest( dest.css ))
    //.pipe(browserSync.stream({match: '**/*.{css}'}))
    .pipe(notify({
        title:"CSS Updated",
        onLast:true
    }));
});


gulp.task("js-error-check", function() {
  return gulp.src( src.js )
    .pipe(plumber({errorHandler: js_error_os_alert}))
    .pipe(uglify({mangle: false}));
});

gulp.task("compile-js-to-min",['js-error-check'], function() {
  return gulp.src( src.js )
    //.pipe(sourcemaps.init())
    //.pipe(jsinclude())
    .pipe(uglify())//{mangle: false}
    .on('error',function(){})
    //.pipe(sourcemaps.write( './'))
    .pipe(gulp.dest( dest.js ))
    //.pipe(browserSync.stream({match: '**/*.{js,map}'}));
});

gulp.task("compile-js", function() {
  return gulp.src( src.js )
    //.pipe(sourcemaps.init())
    //.pipe(jsinclude())
    //.pipe(uglify({mangle: false}))
    .on('error',function(){})
    //.pipe(sourcemaps.write( './'))
    .pipe(rename(function (path) {
      path.basename += ".source";
    }))
    .pipe(gulp.dest( dest.js ))
    //.pipe(browserSync.stream({match: '**/*.{js,map}'}));
});

var css_error_os_alert = function(error){
  notify.onError({
    title: 'SASS error',
    subtitle: error.formatted.split("\n")[0],
    message: error.message.split("\n")[0] + ' on line: ' + error.line,
    sound: "Funk",
  })(error); //Error Notification
  this.emit("end"); //End function
};

var js_error_os_alert = function(error){
  notify.onError({
    title: 'JS error',
    subtitle: error.message.split(" ").slice(1).join(" "),
    message: error.message.split(" ")[0].split("/").pop() + ' on line: ' + error.lineNumber,
    sound: "Funk",
  })(error); //Error Notification
  this.emit("end"); //End function
}
