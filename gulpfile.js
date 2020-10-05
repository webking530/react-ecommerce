var gulp = require('gulp');
var elixir = require('laravel-elixir');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');

elixir.extend('compress', function() {
  gulp.task('compress', function() {
  	//Front End js
    gulp.src('js/*.js').pipe(uglify()).pipe(gulp.dest('min_js'));
    gulp.src('js/chart/*.js').pipe(uglify()).pipe(gulp.dest('min_js/chart'));
    gulp.src('js/tinymce/config/bolt/*.js').pipe(uglify()).pipe(gulp.dest('min_js/tinymce/config/bolt'));
    gulp.src('js/tinymce/js/tinymce/plugins/code/*.js').pipe(uglify()).pipe(gulp.dest('min_js/tinymce/js/tinymce/plugins/code'));
    gulp.src('js/tinymce/js/tinymce/plugins/link/*.js').pipe(uglify()).pipe(gulp.dest('min_js/tinymce/js/tinymce/plugins/link'));
    gulp.src('js/tinymce/js/tinymce/themes/inlite/*.js').pipe(uglify()).pipe(gulp.dest('min_js/tinymce/js/tinymce/themes/inlite'));
    gulp.src('js/tinymce/js/tinymce/themes/modern/*.js').pipe(uglify()).pipe(gulp.dest('min_js/tinymce/js/tinymce/themes/modern'));
    //Front End css
    gulp.src('css/*.css').pipe(uglifycss()).pipe(gulp.dest('min_css'));
    gulp.src('css/merchant/*.css').pipe(uglifycss()).pipe(gulp.dest('min_css/merchant'));
    //Back End js
    gulp.src('admin_assets/dist/js/*.js').pipe(uglify()).pipe(gulp.dest('admin_assets/dist/min_js'));
    //Back End js
    gulp.src('admin_assets/dist/css/*.css').pipe(uglifycss()).pipe(gulp.dest('admin_assets/dist/min_css'));
    gulp.src('admin_assets/dist/css/skins/*.css').pipe(uglifycss()).pipe(gulp.dest('admin_assets/dist/min_css/skins'));
  });
  return this.queueTask('compress');
});

elixir(function(mix) {
    mix.compress();
});

var prettify = require('gulp-jsbeautifier');

gulp.task('prettify_css', function() {
  gulp.src(['css/*.css'])
    .pipe(prettify())
    .pipe(gulp.dest('css'));
});

gulp.task('prettify_js', function() {
  gulp.src(['js/*.js'])
    .pipe(prettify())
    .pipe(gulp.dest('js'));
});

/*var phpcs = require('gulp-phpcs');

gulp.task('default', function () {
    return gulp.src(['app/Http/Controllers/HomeController.php'])
        // Validate files using PHP Code Sniffer
        .pipe(phpcs({
            bin: 'vendor/bin/phpcs',
            standard: 'PSR2',
            warningSeverity: 0
        }))
        // Log all problems that was found
        .pipe(phpcs.reporter('log'));
});*/

/*var phpcbf = require('gulp-phpcbf');
var gutil = require('gutil');

gulp.task('phpcbf', function () {
  return gulp.src(['app/Http/Controllers/HomeController.php'])
  .pipe(phpcbf({
    bin: 'vendor/bin/phpcbf',
    standard: 'PSR2',
    warningSeverity: 0
  }))
  .on('error', gutil.log)
  .pipe(gulp.dest('src'));
});*/