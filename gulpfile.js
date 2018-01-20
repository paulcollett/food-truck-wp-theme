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

// Original Asset Paths
var src = {
  css: 'assets/src/**/*.sass',
  js: ['assets/src/**/*.js','!assets/src/**/_*.js'],
};

// Destination Asset Paths
var dest = {
  css: 'assets/dist',
  js: 'assets/dist',
};

// Main Tasks
gulp.task('default',['clean'],function(){
  gulp.start('build');
});

gulp.task('watch',['default'],function(){
  gulp.watch(src.css, {cwd:'./'}, ['compile-sass-to-css-min']);
  gulp.watch(src.js, {cwd:'./'}, ['compile-js-to-min']);
});

gulp.task('build',[
  'compile-sass-to-css-min',
  'compile-js-to-min'
]);

gulp.task('compile-sass-to-css-min', function(){
  return gulp.src( src.css )
    .pipe(plumber({
        errorHandler: css_error_os_alert
    }))
    .pipe(sourcemaps.init())
    .pipe(sass({
      outputStyle: 'compressed',
      precision: 4,
    }))
    .pipe(autoprefixer({
      browsers: ['ie >= 8', '> 1%']
    }))
    .pipe(sourcemaps.write( './'))
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
    .pipe(sourcemaps.init())
    //.pipe(jsinclude())
    .pipe(uglify({mangle: false}))
    .on('error',function(){})
    .pipe(sourcemaps.write( './'))
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
