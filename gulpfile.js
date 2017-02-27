var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-clean-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cache'),
    prettify = require('gulp-jsbeautifier'),
    vinylpaths = require('vinyl-paths'),
    cmq = require('gulp-combine-mq'),
    merge = require('merge-stream'),
    foreach = require('gulp-flatmap'),
    changed = require('gulp-changed'),
    runSequence = require('run-sequence'),
    browserSync = require('browser-sync').create();
    del = require('del');
sourcemaps = require('gulp-sourcemaps');

// Browser Sync
gulp.task('browsersync',['styles', 'scripts'], function(){
    browserSync.init({
        files: "assets/css/app.css",
        proxy: 'http://localhost/gemsyndicate'
    });
});

// CSS
gulp.task('styles', function(){
    var cssStream = gulp.src([
        'bower_components/smartmenus/src/addons/bootstrap/jquery.smartmenus.bootstrap.css'
    ])
    .pipe(concat('smartmenus.css'));

    var sassStream = gulp.src('assets/scss/style.scss')
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(concat('app.scss'))
    
    var mergeStream = merge(sassStream, cssStream)
        .pipe(concat('app.css'))
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
        .pipe(cmq())
        .pipe(gulp.dest('temp/css'))
        .pipe(rename('app.css'))
        .pipe(prettify())
        .pipe(gulp.dest('assets/css'))
        // .pipe(browserSync.reload({stream: true}))
        .pipe(notify({ message: 'Styles task complete' }));
    
    return mergeStream;
});

// JSHint
gulp.task('lint', function(){
    return gulp.src('assets/js/source/*.js')
        .pipe(jshint('.jshintrc'))
        .pipe(jshint.reporter('default'))
});

// Scripts
gulp.task('scripts', function() {
    return gulp.src([
        'assets/js/source/*.js',
        'bower_components/bootstrap-sass/assets/javascripts/bootstrap.js',
        'bower_components/smartmenus/src/jquery.smartmenus.js',
        'bower_components/smartmenus/src/addons/bootstrap/jquery.smartmenus.bootstrap.js'
    ])
    .pipe(changed('js'))
    .pipe(foreach(function(stream, file){
        return stream
            .pipe(uglify())
            .pipe(rename({suffix: '.min'}))
            .pipe(gulp.dest('temp/js'))
    }))
    .pipe(gulp.dest('assets/js'))
    .pipe(browserSync.reload({stream: true}))
    .pipe(notify({ message: 'Scripts task complete' }));
});

// Clean
gulp.task('clean', function(cb) {
    return gulp.src('temp/*')
    .pipe(vinylpaths(del))
});

// Copy bootstrap fonts to assets folder
gulp.task('copy', function() {
    return gulp.src(['bower_components/bootstrap-sass/assets/fonts/bootstrap/**/*'], {
        base: 'bower_components/bootstrap-sass/assets/fonts'
    })
    .pipe(gulp.dest('assets/fonts'));
});

// Default task
gulp.task('default', function() {
    // gulp.start('styles', 'lint', 'scripts', 'watch');
    runSequence(
        'clean',
        'copy',
        ['styles', 'lint', 'scripts'],
        'watch'
    );
});

// Watch
gulp.task('watch', ['browsersync'], function() {
    // Watch .scss files
    gulp.watch(['assets/scss/*.scss', 'assets/scss/**/*.scss'], ['styles']);

    // Watch .js files
    gulp.watch(['assets/js/vendor/*.js', 'assets/js/source/*.js'], ['scripts']).on('change', browserSync.reload);
});
